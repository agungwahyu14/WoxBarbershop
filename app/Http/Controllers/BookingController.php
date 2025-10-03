<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Loyalty;
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
                ->get();

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

                $data = $query->get();

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
                        </div>' : '<span class="text-gray-400">No customer</span>';
                    })
                    ->addColumn('contact_info', function ($row) {
                        return $row->user && $row->user->no_telepon ?
                            '<div class="flex items-center space-x-2">
                        
                            <span class="font-medium">'.$row->user->no_telepon.'</span>
                        </div>' : '<span class="text-gray-400">No contact</span>';
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

                        return '<span class="text-gray-400">No service</span>';
                    })
                    ->addColumn('hairstyle_info', function ($row) {
                        if ($row->hairstyle) {
                            return '<div class="flex items-center space-x-2">

                            <div>
                                <div class="font-medium text-gray-900">'.$row->hairstyle->name.'</div>
                                <div class="text-sm text-gray-500">'.($row->hairstyle->description ?? 'Classic style').'</div>
                            </div>
                        </div>';
                        }

                        return '<span class="text-gray-400">No hairstyle</span>';
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
                        '.ucfirst(str_replace('_', ' ', $row->status)).'
                    </span>';
                    })
                    ->addColumn('actions', function ($row) {
                        $showUrl = route('admin.bookings.show', $row->id);
                        $actions = '<div class="flex justify-center items-center space-x-2">';

                        // View details button
                        $actions .= '<a href="'.$showUrl.'" 
                  class=" inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors duration-200" 
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

            // Redirect ke halaman booking dengan pesan sukses
            return redirect()->route('bookings.index')
                ->with('success', "Booking berhasil dibuat! Nomor antrian Anda: {$booking->queue_number}")
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

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validasi gagal. Mohon periksa input Anda.');

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
            
            if ($errorCode === 422) {
                // Business hours validation errors
                return redirect()->back()
                    ->with('error', $errorMessage)
                    ->with('error_type', 'business_hours')
                    ->withInput();
            } elseif ($errorCode === 409) {
                // Time slot conflict errors
                return redirect()->back()
                    ->with('warning', $errorMessage)
                    ->with('error_type', 'time_conflict')
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
                ->with('error', 'Booking tidak dapat diubah karena sudah '.$booking->status);
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
            return back()->with('error', 'Booking tidak dapat diubah karena sudah '.$booking->status);
        }

        try {
            DB::beginTransaction();

            $oldDateTime = $booking->date_time;
            $validatedData = $request->validated();

            // Recalculate price if service changed
            if ($booking->service_id != $validatedData['service_id']) {
                $newService = Service::findOrFail($validatedData['service_id']);
                $validatedData['total_price'] = $newService->price;
            }

            // Update queue number if date changed
            $newDateTime = \Carbon\Carbon::parse($validatedData['date_time']);
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
            ]);

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Booking berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating booking', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Booking tidak dapat dibatalkan karena sudah '.$booking->status);
        }

        try {
            $booking->update(['status' => 'cancelled']);

            // Clear caches
            $this->cacheService->clearBookingCaches($booking->date_time);
            $this->cacheService->clearDashboardStats();

            Log::info('Booking cancelled', [
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('bookings.index')
                ->with('success', 'Booking berhasil dibatalkan');

        } catch (\Exception $e) {
            Log::error('Error cancelling booking', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Gagal membatalkan booking');
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

            // ✅ Tambahkan ini jika status = completed
            if ($request->status === 'completed') {
                $this->completeBooking($booking->id);
                Log::info('Loyalty berhasil di tambahkan', [
                    'booking_id' => $booking->id,
                ]);
            }

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
                'message' => 'Status booking berhasil diperbarui',
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

    public function completeBooking($bookingId)
    {
        Log::info('Fungsi completeBooking dipanggil', [
            'booking_id' => $bookingId,
            'waktu' => now()->toDateTimeString(),
        ]);

        DB::beginTransaction();

        try {
            $booking = Booking::findOrFail($bookingId);

            if ($booking->status === 'completed') {
                Log::warning('Booking sudah completed sebelumnya', [
                    'booking_id' => $booking->id,
                ]);
                // return back()->with('warning', 'Booking sudah selesai sebelumnya.');
            }

            $booking->status = 'completed';
            $booking->save();

            Log::info('Status booking diubah menjadi completed', [
                'booking_id' => $booking->id,
            ]);

            $user = $booking->user;

            // Tambahan log sebelum cek loyalty
            if ($user->loyalty) {
                Log::info('User sudah memiliki loyalty', [
                    'user_id' => $user->id,
                    'points' => $user->loyalty->points,
                ]);
            } else {
                Log::info('User belum memiliki loyalty', [
                    'user_id' => $user->id,
                ]);
            }

            $loyalty = $user->loyalty;

            if (! $loyalty) {
                Log::info('Loyalty baru dibuat untuk user', ['user_id' => $user->id]);

                Loyalty::create([
                    'user_id' => $user->id,
                    'points' => 1,
                ]);
            } else {
                Log::info('Loyalty ditemukan, point sebelumnya: '.$loyalty->points, ['user_id' => $user->id]);

                $loyalty->points = ($loyalty->points ?? 0) + 1;

                if ($loyalty->points >= 10) {
                    Log::info('Point mencapai 10, reset ke 0 dan berikan reward (jika ada)', ['user_id' => $user->id]);
                    $loyalty->points = 0;
                }

                $loyalty->save();

                Log::info('Loyalty diupdate, point sekarang: '.$loyalty->points, ['user_id' => $user->id]);
            }

            DB::commit();

            return back()->with('success', 'Booking selesai dan loyalty diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyelesaikan booking', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
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
    }
    
    $fileName = 'bookings' . $period . '_' . now()->format('Ymd_His') . '.csv';

    // Query with filter
    $query = Booking::with(['user', 'service', 'hairstyle']);
    
    if ($month && $year) {
        $query->whereYear('date_time', $year)
              ->whereMonth('date_time', $month);
    } elseif ($year) {
        $query->whereYear('date_time', $year);
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
        }

        // Query with filter
        $query = Booking::with(['user', 'service', 'hairstyle']);
        
        if ($month && $year) {
            $query->whereYear('date_time', $year)
                  ->whereMonth('date_time', $month);
        } elseif ($year) {
            $query->whereYear('date_time', $year);
        }
        
        $bookings = $query->get();

        $pdf = Pdf::loadView('admin.bookings.export_pdf', compact('bookings', 'month', 'year'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('bookings' . $period . '_' . now()->format('Ymd_His') . '.pdf');
    }
}
