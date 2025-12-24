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
use Carbon\Carbon;

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

                $data = $query
                    ->orderBy('created_at', 'desc')
                    ->orderByRaw("FIELD(status, 'pending', 'confirmed', 'in_progress', 'completed', 'cancelled')")
                    ->get();


                Log::info('Bookings data retrieved successfully', [
                    'total_records' => $data->count(),
                ]);

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('customer_info', function ($row) {
                        if ($row->user) {
                            return '<div class="flex items-center space-x-3">
                                <div>
                                    <div class="font-medium text-gray-900">'.$row->user->name.'</div>
                                    <div class="text-sm text-gray-500">'.$row->user->email.'</div>
                                </div>
                            </div>';
                        } else {
                            // Guest booking - show dash
                            return '<span class="text-gray-500">-</span>';
                        }
                    })
                    ->addColumn('name', function ($row) {
                        return $row->name;
                    })
                    ->addColumn('contact_info', function ($row) {
                        if ($row->user && $row->user->no_telepon) {
                            return '<div class="flex items-center space-x-2">
                                <span class="font-medium">'.$row->user->no_telepon.'</span>
                            </div>';
                        } else {
                            // Guest booking or no phone - show dash
                            return '<span class="text-gray-500">-</span>';
                        }
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

                        return '<div class="text-left">
                        <div class="font-medium text-gray-900">'.$date->format('d M Y').'</div>
                  
                    </div>';
                    })
                    ->addColumn('shift_display', function ($row) {
                        if ($row->shift) {
                            $shiftColors = [
                                'morning' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'afternoon' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                            ];
                            $shiftIcons = [
                                'morning' => 'fas fa-sun',
                                'afternoon' => 'fas fa-moon',
                            ];
                            $shiftTimes = [
                                'morning' => '11:00 - 15:00',
                                'afternoon' => '16:00 - 22:00',
                            ];
                            
                            $color = $shiftColors[$row->shift] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            $icon = $shiftIcons[$row->shift] ?? 'fas fa-clock';
                            $time = $shiftTimes[$row->shift] ?? '';
                            
                            return '<div class="text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border '.$color.'">
                                    <i class="'.$icon.' mr-1"></i>
                                    '.__('booking.shift_'.$row->shift).'
                                </span>
                                <div class="text-xs text-gray-500 mt-1">'.$time.'</div>
                            </div>';
                        }
                        
                        return '<span class="text-gray-400 text-xs">-</span>';
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
                    ->rawColumns(['customer_info', 'contact_info', 'service_info', 'hairstyle_info', 'datetime_formatted', 'shift_display', 'queue_display', 'status_badge', 'actions'])
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

    /**
     * Show the form for creating a new booking (Admin/Staff only)
     */
    public function create()
    {
        // Only admin and pegawai can access this
        if (!auth()->user()->hasAnyRole(['admin', 'pegawai'])) {
            abort(403, 'Unauthorized action.');
        }

        // Load necessary data for the form
        $services = Service::where('is_active', true)->get();
        $hairstyles = \App\Models\Hairstyle::all();
        $users = User::role('pelanggan')->get(); // Only get users with pelanggan role

        Log::info('Admin booking create form accessed', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->roles->pluck('name')->implode(','),
            'ip' => request()->ip()
        ]);

        return view('admin.bookings.create', compact('services', 'hairstyles', 'users'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            Log::info('Booking store attempt', [
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
                'ip' => $request->ip()
            ]);

            // Check if this is an admin/staff creating booking
            $isAdminBooking = auth()->user()->hasAnyRole(['admin', 'pegawai']);

            // Validation rules depend on booking type
            $validationRules = [
                'service_id' => 'required|exists:services,id',
                'hairstyle_id' => 'required|exists:hairstyles,id',
                'payment_method' => 'required|in:cash,bank',
                'booking_date' => 'required|date|after_or_equal:today',
                'shift' => 'required|in:morning,afternoon',
                'description' => 'nullable|string|max:1000',
            ];

            $validationMessages = [
                'service_id.required' => __('booking.service_required'),
                'service_id.exists' => __('booking.service_not_found'),
                'hairstyle_id.required' => __('booking.hairstyle_required'),
                'hairstyle_id.exists' => __('booking.hairstyle_not_found'),
                'payment_method.required' => __('booking.payment_method_required'),
                'payment_method.in' => __('booking.payment_method_invalid'),
                'booking_date.required' => __('booking.date_required'),
                'booking_date.date' => __('booking.date_invalid'),
                'booking_date.after_or_equal' => __('booking.date_past'),
                'shift.required' => __('booking.shift_required'),
                'shift.in' => __('booking.shift_invalid'),
                'description.max' => __('booking.description_too_long'),
            ];

            // Admin bookings have different validation
            if ($isAdminBooking) {
                $validationRules['booking_type'] = 'required|in:registered,guest';
                $validationRules['user_id'] = 'required_if:booking_type,registered|nullable|exists:users,id';
                $validationRules['guest_name'] = 'required_if:booking_type,guest|nullable|string|max:255';
                $validationRules['guest_phone'] = 'required_if:booking_type,guest|nullable|string|max:20';
                $validationRules['name'] = 'nullable|string|max:255';

                $validationMessages['booking_type.required'] = 'Tipe booking harus dipilih';
                $validationMessages['user_id.required_if'] = 'Pelanggan harus dipilih untuk tipe pelanggan terdaftar';
                $validationMessages['guest_name.required_if'] = 'Nama pelanggan wajib diisi untuk tipe pelanggan tamu';
                $validationMessages['guest_phone.required_if'] = 'Nomor telepon wajib diisi untuk tipe pelanggan tamu';
            } else {
                // Regular customer booking
                $validationRules['name'] = 'required|string|max:255';
                $validationMessages['name.required'] = __('booking.name_required');
            }

            // Validasi input
            $validated = $request->validate($validationRules, $validationMessages);

            // Get service data for duration and price calculation
            $service = Service::findOrFail($validated['service_id']);
            $serviceDurationMinutes = (int) filter_var($service->duration, FILTER_SANITIZE_NUMBER_INT);
            
            // Fallback to 60 minutes if no duration specified or invalid duration
            if ($serviceDurationMinutes <= 0) {
                $serviceDurationMinutes = 60;
            }

            // Validate selected shift has capacity
            $bookingDate = $validated['booking_date'];
            $selectedShift = $validated['shift'];

            if (!Booking::hasCapacity($bookingDate, $selectedShift, $serviceDurationMinutes)) {
                $availableCapacity = Booking::getAvailableCapacity($bookingDate, $selectedShift);
                $shiftName = $selectedShift === Booking::SHIFT_MORNING ? __('booking.shift_morning') : __('booking.shift_afternoon');
                
                Log::warning('Selected shift has no capacity', [
                    'user_id' => auth()->id(),
                    'booking_date' => $bookingDate,
                    'selected_shift' => $selectedShift,
                    'service_duration' => $serviceDurationMinutes,
                    'available_capacity' => $availableCapacity
                ]);
                
                throw ValidationException::withMessages([
                    'shift' => __('booking.shift_no_capacity', [
                        'shift' => $shiftName,
                        'available' => $availableCapacity,
                        'required' => $serviceDurationMinutes
                    ])
                ]);
            }

            // Calculate booking time based on selected shift
            $shiftTimeRange = Booking::getShiftTimeRange($selectedShift);
            $bookingDateTime = Carbon::parse($bookingDate . ' ' . $shiftTimeRange['start']);

            // Calculate total price
            $totalPrice = $service->price;

            // Calculate queue number (based on same date)
            $queueNumber = Booking::whereDate('date_time', $bookingDate)
                ->count() + 1;

            // Determine user_id and name based on booking type
            $bookingUserId = null;
            $bookingName = '';

            if ($isAdminBooking) {
                // Admin creating booking
                if ($request->booking_type === 'registered') {
                    // Registered customer
                    $bookingUserId = $validated['user_id'];
                    $selectedUser = User::find($bookingUserId);
                    $bookingName = $validated['name'] ?: $selectedUser->name;
                } else {
                    // Guest customer (walk-in)
                    $bookingUserId = null; // No user_id for guests
                    $bookingName = $validated['guest_name'];
                }
            } else {
                // Regular customer booking themselves
                $bookingUserId = auth()->id();
                $bookingName = $validated['name'];
            }

            // Determine booking status
            // Admin cash bookings are completed (walk-in customers get service done immediately)
            // Admin bank bookings are pending (waiting for payment)
            // Customer bookings are always pending initially
            if ($isAdminBooking && $validated['payment_method'] === 'cash') {
                $bookingStatus = 'completed'; // Layanan sudah selesai untuk walk-in dengan cash
            } else {
                $bookingStatus = 'pending'; // Menunggu pembayaran atau konfirmasi
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => $bookingUserId,
                'name' => $bookingName,
                'service_id' => $validated['service_id'],
                'hairstyle_id' => $validated['hairstyle_id'],
                'date_time' => $bookingDateTime,
                'shift' => $selectedShift,
                'queue_number' => $queueNumber,
                'description' => $validated['description'] ?? null,
                'payment_method' => $validated['payment_method'],
                'status' => $bookingStatus,
                'total_price' => $totalPrice,
            ]);

            Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
                'queue_number' => $booking->queue_number,
                'selected_shift' => $selectedShift,
                'service_duration' => $serviceDurationMinutes,
                'is_admin_booking' => $isAdminBooking,
                'payment_method' => $validated['payment_method']
            ]);

            // Create transaction automatically for admin bookings
            $snapToken = null;
            if ($isAdminBooking) {
                // Get customer email - for registered users, use their email; for guests, use null
                $customerEmail = null;
                if ($bookingUserId) {
                    $customerUser = User::find($bookingUserId);
                    $customerEmail = $customerUser ? $customerUser->email : null;
                }

                if ($validated['payment_method'] === 'cash') {
                    // Cash payment - create transaction with settlement status
                    \App\Models\Transaction::create([
                        'order_id' => $booking->id,
                        'transaction_status' => 'settlement',
                        'payment_type' => 'cash',
                        'gross_amount' => $booking->total_price,
                        'transaction_time' => now(),
                        'bank' => null,
                        'va_number' => null,
                        'name' => $booking->name,
                        'email' => $customerEmail,
                    ]);

                    Log::info('Cash transaction created automatically for admin booking', [
                        'booking_id' => $booking->id,
                        'admin_id' => auth()->id(),
                        'amount' => $booking->total_price
                    ]);
                } else {
                    // Bank transfer - create Midtrans transaction and get snap token
                    try {
                        $midtransService = app(\App\Services\MidtransService::class);
                        $snapToken = $midtransService->createTransaction($booking);

                        Log::info('Bank transfer transaction initiated for admin booking', [
                            'booking_id' => $booking->id,
                            'admin_id' => auth()->id(),
                            'snap_token_created' => !empty($snapToken)
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to create Midtrans transaction for admin booking', [
                            'booking_id' => $booking->id,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                }
            }

            DB::commit();

            // For admin bookings, return different response based on payment method
            if ($isAdminBooking) {
                // Return JSON response for AJAX handling in create page
                return response()->json([
                    'success' => true,
                    'message' => __('booking.booking_created_successfully', ['queue_number' => $booking->queue_number]),
                    'payment_method' => $validated['payment_method'],
                    'snap_token' => $snapToken, // For bank transfer
                    'booking_id' => $booking->id,
                    'redirect' => route('admin.bookings.index')
                ]);
            }

            // Check if request is AJAX (for regular customer bookings)
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('booking.booking_created_successfully', ['queue_number' => $booking->queue_number]),
                    'data' => [
                        'booking_id' => $booking->id,
                        'name' => $booking->name,
                        'queue_number' => $booking->queue_number,
                        'date_time' => $booking->date_time->format('d/m/Y H:i'),
                        'shift' => ucfirst($booking->shift),
                        'service_name' => $booking->service->name ?? 'N/A'
                    ],
                    'redirect' => route('bookings.index')
                ]);
            }

            // Redirect with success message (for non-AJAX customer requests)
            return redirect()->route('bookings.index')->with('success', __('booking.booking_created_successfully', ['queue_number' => $booking->queue_number]));

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            Log::warning('Booking validation failed', [
                'user_id' => auth()->id(),
                'validation_errors' => $e->errors(),
                'ip' => $request->ip()
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('booking.validation_failed'),
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error creating booking', [
                'error_message' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('booking.booking_creation_failed'),
                    'error_type' => 'general'
                ], 500);
            }

            return back()->withInput()->with('error', __('booking.booking_creation_failed'));
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

    public function update(Request $request, Booking $booking)
    {
        try {
            DB::beginTransaction();

            Log::info('Booking update attempt', [
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
                'ip' => $request->ip()
            ]);

            // Validasi input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'service_id' => 'required|exists:services,id',
                'hairstyle_id' => 'required|exists:hairstyles,id',
                'payment_method' => 'required|in:cash,bank',
                'booking_date' => 'required|date|after_or_equal:today',
                'shift' => 'required|in:morning,afternoon',
                'description' => 'nullable|string|max:1000',
            ], [
                'name.required' => __('booking.name_required'),
                'service_id.required' => __('booking.service_required'),
                'service_id.exists' => __('booking.service_not_found'),
                'hairstyle_id.required' => __('booking.hairstyle_required'),
                'hairstyle_id.exists' => __('booking.hairstyle_not_found'),
                'payment_method.required' => __('booking.payment_method_required'),
                'payment_method.in' => __('booking.payment_method_invalid'),
                'booking_date.required' => __('booking.date_required'),
                'booking_date.date' => __('booking.date_invalid'),
                'booking_date.after_or_equal' => __('booking.date_past'),
                'shift.required' => __('booking.shift_required'),
                'shift.in' => __('booking.shift_invalid'),
                'description.max' => __('booking.description_too_long'),
            ]);

            // Get service data for duration and price calculation
            $service = Service::findOrFail($validated['service_id']);
            $serviceDurationMinutes = (int) filter_var($service->duration, FILTER_SANITIZE_NUMBER_INT);
            
            // Fallback to 60 minutes if no duration specified or invalid duration
            if ($serviceDurationMinutes <= 0) {
                $serviceDurationMinutes = 60;
            }

            // Check shift capacity (excluding current booking)
            $bookingDate = $validated['booking_date'];
            $selectedShift = $validated['shift'];
            
            // Calculate booked duration for the shift, excluding current booking
            $bookedDuration = Booking::with('service')
                ->byDate($bookingDate)
                ->byShift($selectedShift)
                ->active()
                ->where('id', '!=', $booking->id) // Exclude current booking
                ->get()
                ->sum(function ($existingBooking) {
                    $duration = $existingBooking->service ? $existingBooking->service->duration : '60';
                    return (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT);
                });
            
            $shiftCapacity = $selectedShift === Booking::SHIFT_MORNING 
                ? Booking::SHIFT_MORNING_CAPACITY 
                : Booking::SHIFT_AFTERNOON_CAPACITY;
            
            $availableCapacity = $shiftCapacity - $bookedDuration;
            
            if ($availableCapacity < $serviceDurationMinutes) {
                Log::warning('Booking update insufficient capacity', [
                    'booking_id' => $booking->id,
                    'user_id' => auth()->id(),
                    'booking_date' => $bookingDate,
                    'selected_shift' => $selectedShift,
                    'required_duration' => $serviceDurationMinutes,
                    'available_capacity' => $availableCapacity
                ]);
                
                $shiftName = $selectedShift === 'morning' 
                    ? __('booking.shift_morning') 
                    : __('booking.shift_afternoon');
                
                throw ValidationException::withMessages([
                    'shift' => __('booking.shift_no_capacity', [
                        'shift' => $shiftName,
                        'available' => $availableCapacity,
                        'required' => $serviceDurationMinutes
                    ])
                ]);
            }

            // Calculate booking time based on selected shift
            $shiftTimeRange = Booking::getShiftTimeRange($selectedShift);
            $bookingDateTime = Carbon::parse($bookingDate . ' ' . $shiftTimeRange['start']);

            // Calculate total price
            $totalPrice = $service->price;

            // Update booking data
            $booking->update([
                'name' => $validated['name'],
                'service_id' => $validated['service_id'],
                'hairstyle_id' => $validated['hairstyle_id'],
                'date_time' => $bookingDateTime,
                'shift' => $selectedShift,
                'description' => $validated['description'] ?? null,
                'payment_method' => $validated['payment_method'],
                'total_price' => $totalPrice,
            ]);

            DB::commit();

            Log::info('Booking updated successfully', [
                'booking_id' => $booking->id,
                'user_id' => auth()->id()
            ]);

            // Check if request is AJAX
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('booking.booking_updated_successfully'),
                    'data' => [
                        'booking_id' => $booking->id,
                        'name' => $booking->name,
                        'date_time' => $booking->date_time->format('d/m/Y H:i'),
                        'service_name' => $booking->service->name ?? 'N/A'
                    ],
                    'redirect' => route('bookings.show', $booking)
                ]);
            }

            return redirect()->route('bookings.show', $booking)
                ->with('success', __('booking.booking_updated_successfully'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            Log::warning('Booking update validation failed', [
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
                'validation_errors' => $e->errors(),
                'ip' => $request->ip()
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('booking.validation_failed'),
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error updating booking', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('booking.update_failed'),
                ], 500);
            }

            return back()->with('error', __('booking.update_failed') . ': ' . $e->getMessage());
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

    /**
     * Find alternative available time slots for the given date and service duration
     */
    private function findAlternativeSlots($date, $serviceDurationMinutes, $maxSlots = 3)
    {
        $alternativeSlots = [];
        
        // Business hours (11:00 AM to 10:00 PM)
        $businessStart = Carbon::parse($date . ' 11:00');
        $businessEnd = Carbon::parse($date . ' 22:00');
        
        // Get all existing bookings for the date (excluding cancelled)
        $existingBookings = Booking::with('service')
            ->where('status', '!=', 'cancelled')
            ->whereDate('date_time', $date)
            ->orderBy('date_time')
            ->get();
        
        // Check each 30-minute slot during business hours
        $currentSlot = $businessStart->copy();
        $latestStartTime = $businessEnd->copy()->subMinutes($serviceDurationMinutes);
        
        while ($currentSlot->lte($latestStartTime) && count($alternativeSlots) < $maxSlots) {
            $slotEnd = $currentSlot->copy()->addMinutes($serviceDurationMinutes);
            
            // Check if this slot conflicts with any existing booking
            $hasConflict = $existingBookings->contains(function ($existingBooking) use ($currentSlot, $slotEnd) {
                $existingStart = Carbon::parse($existingBooking->date_time);
                $existingDuration = (int) filter_var($existingBooking->service->duration ?? '60', FILTER_SANITIZE_NUMBER_INT);
                $existingEnd = $existingStart->copy()->addMinutes($existingDuration);
                
                return ($currentSlot < $existingEnd) && ($slotEnd > $existingStart);
            });
            
            if (!$hasConflict) {
                $alternativeSlots[] = $currentSlot->format('H:i');
            }
            
            $currentSlot->addMinutes(30);
        }
        
        return $alternativeSlots;
    }

    /**
     * API endpoint to check available time slots for a specific date and service
     */
    public function checkAvailableSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:services,id'
        ]);

        $service = Service::findOrFail($request->service_id);
        $serviceDurationMinutes = (int) filter_var($service->duration, FILTER_SANITIZE_NUMBER_INT);
        
        if ($serviceDurationMinutes <= 0) {
            $serviceDurationMinutes = 60;
        }

        $availableSlots = $this->findAlternativeSlots($request->date, $serviceDurationMinutes, 10);

        return response()->json([
            'success' => true,
            'available_slots' => $availableSlots,
            'service_duration' => $serviceDurationMinutes
        ]);
    }
}
