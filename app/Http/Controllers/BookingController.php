<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Services\BookingService;
use App\Services\CacheService;
use App\Services\QueueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    protected $bookingService;

    protected $queueService;

    protected $cacheService;

    public function __construct(
        BookingService $bookingService,
        QueueService $queueService,
        CacheService $cacheService
    ) {
        $this->bookingService = $bookingService;
        $this->queueService = $queueService;
        $this->cacheService = $cacheService;

        $this->middleware('auth');

    }

    public function index(Request $request)
    {
        if (! auth()->user()->hasRole('admin') && ! auth()->user()->hasRole('pegawai')) {
            $user = auth()->user(); // ✅ Tambahkan ini

            $bookings = Booking::with(['service', 'hairstyle'])
                ->where('user_id', $user->id)
                ->orderByDesc('id')
                ->paginate(6); // ✅ Ubah dari get() ke paginate(6)

            return view('bookings.index', compact('bookings'));
        }

        Log::info('BookingController@index accessed', [
            'is_ajax' => $request->ajax(),
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->ajax()) {
            Log::info('Processing AJAX request for bookings datatable');

            try {
                $query = Booking::with(['user', 'service', 'hairstyle']);

                // Apply status filter
                if ($request->has('status_filter') && ! empty($request->status_filter)) {
                    $query->where('status', $request->status_filter);
                    Log::info('Status filter applied', ['status' => $request->status_filter]);
                }

                // Apply month filter
                if ($request->has('month_filter') && ! empty($request->month_filter)) {
                    $query->whereMonth('date_time', $request->month_filter);
                    Log::info('Month filter applied', ['month' => $request->month_filter]);
                }

                // Apply year filter
                if ($request->has('year_filter') && ! empty($request->year_filter)) {
                    $query->whereYear('date_time', $request->year_filter);
                    Log::info('Year filter applied', ['year' => $request->year_filter]);
                }

                $data = $query->orderByDesc('id')->get();

                Log::info('Bookings data retrieved successfully', [
                    'total_records' => $data->count(),
                ]);

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('customer_info', function ($row) {
                        return $row->user ?
                            '<div class="flex items-center space-x-3">
                         
                            <div>
                                <div class="font-medium text-gray-900">'.$row->user->name.'</div>
                                <div class="text-sm text-gray-500">'.$row->user->email.'</div>
                            </div>
                        </div>' : '<span class="text-gray-400">'.__('booking.no_customer').'</span>';
                    })
                    ->addColumn('name', function ($row) {
                        return $row->name ;
                    })
                    ->addColumn('contact_info', function ($row) {
                        return $row->user && $row->user->no_telepon ?
                            '<div class="flex items-center space-x-2">
                        
                            <span class="font-medium">'.$row->user->no_telepon.'</span>
                        </div>' : '<span class="text-gray-400">'.__('booking.no_contact').'</span>';
                    })
                    ->addColumn('service_info', function ($row) {
                        if ($row->service) {
                            $icon = $this->getServiceIcon($row->service->name);

                            return '<div class="flex items-center space-x-2">
                            
                            <div>
                                <div class="font-medium text-gray-900">'.$row->service->name.'</div>
                                <div class="text-sm text-gray-500">Rp '.number_format($row->service->price, 0, ',', '.').'</div>
                            </div>
                        </div>';
                        }

                        return '<span class="text-gray-400">'.__('booking.no_service').'</span>';
                    })
                    ->addColumn('hairstyle_info', function ($row) {
                        if ($row->hairstyle) {
                            $locale = app()->getLocale();
                            $description = '';
                            
                            if ($locale === 'en' && !empty($row->hairstyle->description_en)) {
                                $description = $row->hairstyle->description_en;
                            } elseif ($locale === 'id' && !empty($row->hairstyle->description_in)) {
                                $description = $row->hairstyle->description_in;
                            } else {
                                $description = $row->hairstyle->description;
                            }
                            
                            return '<div class="flex items-center space-x-2">

                            <div>
                                <div class="font-medium text-gray-900">'.$row->hairstyle->name.'</div>
                                <div class="text-sm text-gray-500">'.($description ?: __('booking.classic_style')).'</div>
                            </div>
                        </div>';
                        }

                        return '<span class="text-gray-400">'.__('booking.no_hairstyle').'</span>';
                    })
                    ->addColumn('datetime_formatted', function ($row) {
                        $date = \Carbon\Carbon::parse($row->date_time);

                        return '<div class="text-center">
                        <div class="font-medium text-gray-900">'.$date->format('M d, Y').'</div>
                        <div class="text-sm text-gray-500">'.$date->format('H:i A').'</div>
                        <div class="text-xs text-gray-400">'.$date->diffForHumans().'</div>
                    </div>';
                    })
                    ->addColumn('queue_display', function ($row) {
                        return '<div class="text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 rounded-full font-semibold">
                            '.($row->queue_number ?? 0).'
                        </span>
                    </div>';
                    })
                    ->addColumn('status_badge', function ($row) {
                        $colors = [
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'in_progress' => 'bg-orange-100 text-orange-800 border-orange-200',
                            'completed' => 'bg-green-100 text-green-800 border-green-200',
                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                        ];
                        $icons = [
                            'pending' => 'fas fa-clock',
                            'confirmed' => 'fas fa-check',
                            'in_progress' => 'fas fa-cut',
                            'completed' => 'fas fa-check-circle',
                            'cancelled' => 'fas fa-times-circle',
                        ];

                        $color = $colors[$row->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        $icon = $icons[$row->status] ?? 'fas fa-question-circle';

                        return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border '.$color.'">
                        <i class="'.$icon.' mr-1"></i>
                        '.__('booking.status_'.$row->status).'
                    </span>';
                    })
                    ->addColumn('actions', function ($row) {
                        $showUrl = route('admin.bookings.show', $row->id);
                        $actions = '<div class="flex justify-center items-center space-x-2">';

                        // View details button
                        $actions .= '<a href="'.$showUrl.'" 
                  class=" inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 transition-colors duration-200" 
                  title="View Details">
                <i class="fas fa-eye text-sm"></i>
            </a>';

                        // Status-specific actions
                        switch ($row->status) {
                            case 'pending':
                                $actions .= '<button onclick="confirmBooking('.$row->id.')" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 transition-colors duration-200" 
                                               title="Confirm Booking">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>';
                                break;
                            case 'confirmed':
                                $actions .= '<button onclick="startService('.$row->id.')" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-100 hover:bg-orange-200 text-orange-600 transition-colors duration-200" 
                                               title="Start Service">
                                            <i class="fas fa-play text-sm"></i>
                                        </button>';
                                break;
                            case 'in_progress':
                                $actions .= '<button onclick="completeService('.$row->id.')" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-100 hover:bg-purple-200 text-purple-600 transition-colors duration-200" 
                                               title="Complete Service">
                                            <i class="fas fa-flag-checkered text-sm"></i>
                                        </button>';
                                break;
                        }

                        // Cancel/Delete button (only for pending, confirmed, and in_progress bookings)
                        if (!in_array($row->status, ['completed', 'cancelled'])) {
                            $actions .= '<button onclick="cancelBooking('.$row->id.')" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors duration-200" 
                                           title="Cancel Booking">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>';
                        }

                        $actions .= '</div>';

                        return $actions;
                    })
                    ->rawColumns(['customer_info', 'contact_info', 'service_info', 'hairstyle_info', 'datetime_formatted', 'queue_display', 'status_badge', 'actions'])
                    ->make(true);

            } catch (\Exception $e) {
                Log::error('Error in BookingController@index AJAX', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'error' => 'Failed to retrieve bookings data',
                ], 500);
            }
        }

        $services = \App\Models\Service::all();
        $hairstyles = \App\Models\Hairstyle::all();

        // $hairstyles = $this->cacheService->getActiveHairstyles();

        return view('admin.bookings.index', compact('services', 'hairstyles'));
    }

    private function getServiceIcon($serviceName)
    {
        $serviceName = strtolower($serviceName);

        $icons = [
            'potong rambut' => 'fas fa-cut',
            'hair cut' => 'fas fa-cut',
            'cukur' => 'fas fa-cut',
            'shampoo' => 'fas fa-soap',
            'cuci rambut' => 'fas fa-soap',
            'styling' => 'fas fa-magic',
            'hair styling' => 'fas fa-magic',
            'pewarnaan' => 'fas fa-palette',
            'hair color' => 'fas fa-palette',
            'coloring' => 'fas fa-palette',
            'creambath' => 'fas fa-bath',
            'treatment' => 'fas fa-spa',
            'facial' => 'fas fa-user-circle',
            'massage' => 'fas fa-hand-sparkles',
            'keratin' => 'fas fa-fire',
            'smoothing' => 'fas fa-wind',
            'perm' => 'fas fa-snowflake',
        ];

        foreach ($icons as $keyword => $icon) {
            if (strpos($serviceName, $keyword) !== false) {
                return $icon;
            }
        }

        return 'fas fa-scissors'; // Default barber icon
    }

    // public function create()
    // {
    //     $services = $this->cacheService->getActiveServices();
    //     $hairstyles = $this->cacheService->getActiveHairstyles();

    //     return view('bookings.create', compact('services', 'hairstyles'));
    // }

    public function store(BookingRequest $request)
    {
        try {
            DB::beginTransaction();

            // Simpan booking dari data tervalidasi
            $validated = $request->validated();
            
            Log::info('Booking store attempt', [
                'user_id' => auth()->id(),
                'validated_data' => $validated,
                'ip' => $request->ip()
            ]);
            
            $booking = $this->bookingService->createBooking($validated);

            // Clear cache terkait booking dan dashboard
            $this->cacheService->clearBookingCaches($booking->date_time);
            $this->cacheService->clearDashboardStats();

            DB::commit();

            // Log sukses
            Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
                'date_time' => $booking->date_time,
                'service_id' => $booking->service_id,
                'queue_number' => $booking->queue_number
            ]);

            // Check if request is AJAX
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('booking.booking_created_successfully', ['queue_number' => $booking->queue_number]),
                    'data' => [
                        'booking_id' => $booking->id,
                        'name' => $booking->name,
                        'queue_number' => $booking->queue_number,
                        'date_time' => $booking->date_time->format('d/m/Y H:i'),
                        'service_name' => $booking->service->name ?? 'N/A'
                    ],
                    'redirect' => route('bookings.index')
                ]);
            }

            // Redirect ke halaman booking dengan pesan sukses
            return redirect()->route('bookings.index')
                ->with('success', __('booking.booking_created_successfully', ['queue_number' => $booking->queue_number]))
                ->with('booking_success', [
                    'name' => $booking->name,
                    'queue_number' => $booking->queue_number,
                    'date_time' => $booking->date_time->format('d/m/Y H:i'),
                    'service_name' => $booking->service->name ?? 'N/A'
                ]);

        } catch (ValidationException $e) {
            DB::rollback();
            
            // Handle validation exceptions specifically
            Log::warning('Booking validation failed', [
                'user_id' => auth()->id(),
                'validation_errors' => $e->errors(),
                'ip' => $request->ip()
            ]);

            // Check if request is AJAX for validation errors
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('booking.validation_failed'),
                    'errors' => $e->errors()
                ], 422);
            }

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', __('booking.validation_failed'));

        } catch (\Exception $e) {
            DB::rollback();

            // Log error dengan detail
            Log::error('Error creating booking', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            // Handle different error types with appropriate messages
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            
            // Check if request is AJAX for general errors
            if ($request->wantsJson() || $request->ajax()) {
                $statusCode = 500; // Default server error
                
                if ($errorCode === 422) {
                    $statusCode = 422;
                    $message = $errorMessage ?: __('booking.business_hours_validation_failed');
                } elseif ($errorCode === 409) {
                    $statusCode = 409;
                    $message = $errorMessage ?: __('booking.schedule_conflict');
                } elseif ($errorCode === 423) {
                    $statusCode = 423;
                    $message = $errorMessage ?: __('booking.daily_quota_exceeded');
                } else {
                    $message = __('booking.booking_creation_failed');
                }
                
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'error_type' => $errorCode === 422 ? 'business_hours' : ($errorCode === 409 ? 'time_conflict' : ($errorCode === 423 ? 'quota_exceeded' : 'general'))
                ], $statusCode);
            }
            
            if ($errorCode === 422) {
                // Business hours validation errors
                return redirect()->back()
                    ->with('error', $errorMessage)
                    ->with('error_type', 'business_hours')
                    ->withInput();
            } elseif ($errorCode === 409) {
                // Time slot conflict errors - provide alternative slots
                try {
                    $requestedTime = Carbon::parse($request->date_time);
                    $service = \App\Models\Service::find($request->service_id);
                    $alternativeSlots = $this->bookingService->getAlternativeSlots(
                        $requestedTime, 
                        $service ? $service->duration : 60
                    );
                    
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => $errorMessage,
                            'error_type' => 'time_conflict',
                            'alternative_slots' => $alternativeSlots
                        ], 409);
                    }
                    
                    return redirect()->back()
                        ->with('warning', $errorMessage)
                        ->with('error_type', 'time_conflict')
                        ->with('alternative_slots', $alternativeSlots)
                        ->withInput();
                } catch (\Exception $altException) {
                    Log::warning('Failed to get alternative slots', [
                        'error' => $altException->getMessage()
                    ]);
                }
                
                return redirect()->back()
                    ->with('warning', $errorMessage)
                    ->with('error_type', 'time_conflict')
                    ->withInput();
            } elseif ($errorCode === 423) {
                // Quota exceeded errors
                return redirect()->back()
                    ->with('error', $errorMessage)
                    ->with('error_type', 'quota_exceeded')
                    ->withInput();
            } else {
                // General errors
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan saat membuat booking. Silakan coba lagi.')
                    ->with('error_type', 'general')
                    ->withInput();
            }
        }
    }

    public function show(Booking $booking)
    {
        try {
            // Authorization check
            $user = auth()->user();

            // Admin and pegawai can view all bookings, customers can only view their own
            if (! $user->hasAnyRole(['admin', 'pegawai']) && $booking->user_id !== $user->id) {
                abort(403, 'Unauthorized access to booking.');
            }

            // Load all necessary relationships
              $booking->load([
            'user' => function ($query) {
                $query->select('id', 'name', 'email', 'no_telepon');
            },
            'service' => function ($query) {
                $query->select('id', 'name', 'description', 'price', 'duration', 'is_active');
            },
            'hairstyle' => function ($query) {
                $query->latest()->with(['user' => function ($subQuery) {
                    $subQuery->select('id', 'name');
                }]);
            },
            'transaction' => function ($query) {
                $query->select('id', 'order_id', 'payment_type', 'transaction_status', 'gross_amount', 'bank', 'va_number', 'transaction_time', 'name', 'email');
            },
        ]);

            // Get queue information
            $queueStatus = null;
            $queuePosition = null;
            $estimatedWaitTime = null;

            try {
                $queueStatus = $this->cacheService->getQueueStatus($booking->date_time);
                $queuePosition = $this->queueService->getQueuePosition($booking);

                // Calculate estimated wait time (assuming 45 minutes per booking on average)
                if ($queuePosition && $queuePosition > 1) {
                    $estimatedWaitTime = ($queuePosition - 1) * 45; // minutes
                }
            } catch (\Exception $e) {
                Log::warning('Failed to get queue information', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Get available time slots for rescheduling (next 7 days)
            $availableSlots = [];
            if (in_array($booking->status, ['pending', 'confirmed'])) {
                try {
                    $availableSlots = $this->bookingService->getAvailableTimeSlots(
                        now()->toDateString(),
                        now()->addDays(7)->toDateString(),
                        $booking->service_id
                    );
                } catch (\Exception $e) {
                    Log::warning('Failed to get available slots', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Check if booking can be modified
            $canModify = in_array($booking->status, ['pending', 'confirmed']) &&
                        ($user->hasAnyRole(['admin', 'pegawai']) || $booking->user_id === $user->id);

            // Check if booking can be cancelled
            $canCancel = $booking->status !== 'completed' && $booking->status !== 'cancelled' &&
                        ($user->hasAnyRole(['admin', 'pegawai']) || $booking->user_id === $user->id);

            // Get service recommendations based on this booking
            $recommendations = [];
            if ($booking->service && $booking->hairstyle) {
                try {
                    $recommendations = $this->getServiceRecommendations($booking);
                } catch (\Exception $e) {
                    Log::warning('Failed to get recommendations', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Log the view access
            Log::info('Booking details viewed', [
                'booking_id' => $booking->id,
                'viewed_by' => $user->id,
                'user_role' => $user->roles->pluck('name')->implode(','),
                'booking_status' => $booking->status,
            ]);

            // Prepare data for view
            $viewData = compact(
                'booking',
                'queueStatus',
                'queuePosition',
                'estimatedWaitTime',
                'availableSlots',
                'canModify',
                'canCancel',
                'recommendations'
            );

            // Return different views based on user role
            if ($user->hasAnyRole(['admin', 'pegawai'])) {
                return view('admin.bookings.show', $viewData);
            } else {
                return view('bookings.show', $viewData);
            }

        } catch (\Exception $e) {
            Log::error('Error in BookingController@show', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat detail booking.');
        }
    }

    /**
     * Get service recommendations based on current booking
     */
    // private function getServiceRecommendations(Booking $booking)
    // {
    //     // Get services that are commonly booked together
    //     $relatedServices = Service::where('is_active', true)
    //         ->where('id', '!=', $booking->service_id)
    //         ->whereIn('id', function($query) use ($booking) {
    //             $query->select('service_id')
    //                   ->from('bookings')
    //                   ->whereIn('user_id', function($subQuery) use ($booking) {
    //                       $subQuery->select('user_id')
    //                                ->from('bookings')
    //                                ->where('service_id', $booking->service_id);
    //                   })
    //                   ->groupBy('service_id')
    //                   ->havingRaw('COUNT(*) > 1');
    //         })
    //         ->limit(3)
    //         ->get(['id', 'name', 'description', 'price', 'duration']);

    //     // If no related services found, get popular services
    //     if ($relatedServices->isEmpty()) {
    //         $relatedServices = Service::where('is_active', true)
    //             ->where('id', '!=', $booking->service_id)
    //             ->withCount('bookings')
    //             ->orderBy('bookings_count', 'desc')
    //             ->limit(3)
    //             ->get(['id', 'name', 'description', 'price', 'duration']);
    //     }

    //     return $relatedServices;
    // }

    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);

        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', __('booking.booking_cannot_be_edited_status', ['status' => $booking->status]));
        }

        $services = $this->cacheService->getActiveServices();
        $hairstyles = $this->cacheService->getActiveHairstyles();

        // Check if services and hairstyles exist
        if ($services->isEmpty()) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Tidak ada layanan aktif tersedia untuk edit booking.');
        }

        if ($hairstyles->isEmpty()) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Tidak ada gaya rambut aktif tersedia untuk edit booking.');
        }

        return view('bookings.edit', compact('booking', 'services', 'hairstyles'));
    }

    public function update(BookingRequest $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', __('booking.booking_cannot_be_edited_status', ['status' => $booking->status]));
        }

        try {
            DB::beginTransaction();

            $oldDateTime = $booking->date_time;
            $validatedData = $request->validated();
            $newDateTime = \Carbon\Carbon::parse($validatedData['date_time']);

            // Apply same business logic validation as create booking
            $validation = $this->bookingService->validateBusinessHours($newDateTime);
            
            if (!$validation['is_valid']) {
                $errorMessage = implode('. ', $validation['errors']);
                
                Log::warning('Booking update validation failed', [
                    'booking_id' => $booking->id,
                    'user_id' => auth()->id(),
                    'errors' => $validation['errors'],
                    'suggestions' => $validation['suggestions'],
                    'old_datetime' => $oldDateTime->format('Y-m-d H:i:s'),
                    'new_datetime' => $newDateTime->format('Y-m-d H:i:s')
                ]);
                
                throw new \Exception($errorMessage, 422); // 422 = Unprocessable Entity
            }

            // Check if service extends beyond business hours
            $service = Service::findOrFail($validatedData['service_id']);
            $bookingEndTime = $newDateTime->copy()->addMinutes($service->duration);
            if (!$this->bookingService->isWithinBusinessHours($bookingEndTime->subMinute())) {
                Log::warning('Updated service would extend beyond business hours', [
                    'booking_id' => $booking->id,
                    'start_time' => $newDateTime->format('H:i'),
                    'service_duration' => $service->duration,
                    'end_time' => $bookingEndTime->format('H:i')
                ]);
                
                throw new \Exception('Layanan akan berakhir setelah jam tutup (22:00). Silakan pilih waktu yang lebih awal.', 422);
            }

            // Check time slot availability for new time
            $slotDetails = $this->bookingService->getSlotAvailabilityDetails($newDateTime, $service->duration);
            
            if (!$slotDetails['is_available']) {
                // Exclude current booking from conflict check
                $conflictingBookings = collect($slotDetails['conflicting_bookings'])
                    ->filter(function ($conflict) use ($booking) {
                        return $conflict['id'] !== $booking->id;
                    });
                
                if ($conflictingBookings->isNotEmpty()) {
                    $conflictInfo = '';
                    if ($conflictingBookings->isNotEmpty()) {
                        $conflictInfo = 'Bertabrakan dengan booking: ';
                        $conflictInfo .= $conflictingBookings->map(function ($booking) {
                            return "{$booking['service_name']} ({$booking['start_time']}-{$booking['end_time']})";
                        })->join(', ');
                    }
                    
                    try {
                        $nextAvailable = $this->bookingService->findNextAvailableSlot($newDateTime, $service->duration);
                        $nextSlotText = $nextAvailable->format('d/m/Y H:i');
                    } catch (\Exception $e) {
                        $nextSlotText = 'tidak ada slot tersedia dalam 2 hari ke depan';
                    }
                    
                    Log::warning('Update time slot not available - detailed', [
                        'booking_id' => $booking->id,
                        'requested_time' => $newDateTime->format('Y-m-d H:i:s'),
                        'conflicting_bookings' => $conflictingBookings->toArray(),
                        'next_available' => $nextSlotText
                    ]);
                    
                    $errorMessage = "Slot waktu tidak tersedia. {$conflictInfo} Slot terdekat: {$nextSlotText}";
                    throw new \Exception($errorMessage, 409); // 409 = Conflict
                }
            }

            // Recalculate price if service changed
            if ($booking->service_id != $validatedData['service_id']) {
                $validatedData['total_price'] = $service->price;
            }

            // Update queue number if date changed
            if ($oldDateTime->format('Y-m-d') !== $newDateTime->format('Y-m-d')) {
                // Get the next queue number for the new date
                $maxQueue = Booking::whereDate('date_time', $newDateTime->format('Y-m-d'))
                    ->max('queue_number');
                $validatedData['queue_number'] = $maxQueue ? $maxQueue + 1 : 1;
            }

            $booking->update($validatedData);

            // Clear caches for both old and new dates
            $this->cacheService->clearBookingCaches($oldDateTime);
            $this->cacheService->clearBookingCaches($booking->date_time);
            $this->cacheService->clearDashboardStats();

            DB::commit();

            Log::info('Booking updated successfully', [
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
                'changes' => $booking->getChanges(),
                'old_datetime' => $oldDateTime->format('Y-m-d H:i:s'),
                'new_datetime' => $newDateTime->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('bookings.show', $booking)
                ->with('success', __('booking.booking_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating booking', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'user_id' => auth()->id(),
            ]);

            // Handle different error types with appropriate messages
            $errorCode = $e->getCode();
            
            if ($errorCode === 422) {
                // Business hours validation errors
                return redirect()->back()
                    ->with('error', $e->getMessage())
                    ->with('error_type', 'business_hours')
                    ->withInput();
            } elseif ($errorCode === 409) {
                // Time slot conflict errors
                return redirect()->back()
                    ->with('warning', $e->getMessage())
                    ->with('error_type', 'time_conflict')
                    ->withInput();
            } else {
                // General errors
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan saat memperbarui booking: ' . $e->getMessage())
                    ->with('error_type', 'general')
                    ->withInput();
            }
        }
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Booking tidak dapat dibatalkan karena sudah '.$booking->status);
        }

        try {
    DB::beginTransaction();

    // Cancel booking and payment status
    $paymentController = app(\App\Http\Controllers\PaymentController::class);
    $transactionCancelled = $paymentController->cancelTransaction($booking);

    // Update status booking menjadi dibatalkan
    $booking->update([
        'status' => 'cancelled',
        'payment_status' => 'cancelled',
    ]);

    // Clear caches
    $this->cacheService->clearBookingCaches($booking->date_time);
    $this->cacheService->clearDashboardStats();

    DB::commit();

    Log::info('Booking and transaction cancelled', [
        'booking_id' => $booking->id,
        'user_id' => auth()->id(),
        'transaction_cancelled' => $transactionCancelled
    ]);

    $message = __('admin.booking_cancelled_successfully');
    if ($transactionCancelled) {
        $message = __('admin.booking_and_transaction_cancelled');
    }

    return redirect()->route('bookings.index')
        ->with('success', $message);

} catch (\Exception $e) {
    DB::rollBack();
    
    Log::error('Error cancelling booking', [
        'booking_id' => $booking->id,
        'error' => $e->getMessage(),
        'user_id' => auth()->id(),
    ]);

    return back()->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
}

    }

    /**
     * Update booking status (for admin/staff)
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $this->authorize('updateStatus', $booking);

        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        try {
            $this->bookingService->updateBookingStatus($booking, $request->status);

            // Clear caches
            $this->cacheService->clearBookingCaches($booking->date_time);
            $this->cacheService->clearDashboardStats();

            Log::info('Booking status updated', [
                'booking_id' => $booking->id,
                'new_status' => $request->status,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => __('admin.booking_status_updated_successfully'),
                'new_status' => $request->status,
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating booking status', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get booking statistics for real-time updates
     */
    public function getStatistics()
    {
        try {
            $statistics = [
                'today_bookings' => Booking::whereDate('date_time', today())->count(),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
                'progress_bookings' => Booking::where('status', 'in_progress')->count(),
                'completed_bookings' => Booking::where('status', 'completed')->whereDate('date_time', today())->count(),
            ];

            return response()->json([
                'success' => true,
                'today_bookings' => $statistics['today_bookings'],
                'pending_bookings' => $statistics['pending_bookings'],
                'progress_bookings' => $statistics['progress_bookings'],
                'completed_bookings' => $statistics['completed_bookings'],
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting booking statistics', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics',
            ], 500);
        }
    }


    public function exportCsv(Request $request): StreamedResponse
{
    $month = $request->get('month');
    $year = $request->get('year');
    
    $period = '';
    if ($month && $year) {
        $period = '_' . \Carbon\Carbon::create($year, $month)->format('M_Y');
    } elseif ($year) {
        $period = '_' . $year;
    } elseif ($month) {
        $monthName = \Carbon\Carbon::create(null, $month)->format('M');
        $period = '_' . $monthName;
    }
    
    $fileName = 'bookings' . $period . '_' . now()->format('Ymd_His') . '.csv';

    // Query with filter
    $query = Booking::with(['user', 'service', 'hairstyle']);
    
    if ($month && $year) {
        $query->whereYear('date_time', $year)
              ->whereMonth('date_time', $month);
    } elseif ($year) {
        $query->whereYear('date_time', $year);
    } elseif ($month) {
        $query->whereMonth('date_time', $month);
    }
    
    $bookings = $query->get();

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ];

    $callback = function () use ($bookings) {
        $handle = fopen('php://output', 'w');

        // Header CSV
        fputcsv($handle, ['No', 'Customer Name', 'Service', 'Hairstyle', 'Date & Time', 'Queue', 'Status']);

        // Data rows
        foreach ($bookings as $i => $booking) {
            fputcsv($handle, [
                $i + 1, // Nomor urut
                $booking->user->name ?? '-', // Customer Name
                $booking->service->name ?? '-', // Service Name
                $booking->hairstyle->name ?? '-', // Hairstyle Name
                $booking->date_time ? $booking->date_time->format('d/m/Y H:i') : '-', // Date & Time
                $booking->queue_number ?? '-', // Queue Number
                ucfirst($booking->status ?? '-'), // Status
            ]);
        }

        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}

    /**
     * Export bookings to PDF with filter
     */
    public function exportPdf(Request $request)
    {
        $month = $request->get('month');
        $year = $request->get('year');
        
        $period = '';
        if ($month && $year) {
            $period = '_' . \Carbon\Carbon::create($year, $month)->format('M_Y');
        } elseif ($year) {
            $period = '_' . $year;
        } elseif ($month) {
            $monthName = \Carbon\Carbon::create(null, $month)->format('M');
            $period = '_' . $monthName;
        }

        // Query with filter
        $query = Booking::with(['user', 'service', 'hairstyle']);
        
        if ($month && $year) {
            $query->whereYear('date_time', $year)
                  ->whereMonth('date_time', $month);
        } elseif ($year) {
            $query->whereYear('date_time', $year);
        } elseif ($month) {
            $query->whereMonth('date_time', $month);
        }
        
        $bookings = $query->get();

        $pdf = Pdf::loadView('admin.bookings.export_pdf', compact('bookings', 'month', 'year'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('bookings' . $period . '_' . now()->format('Ymd_His') . '.pdf');
    }
}
