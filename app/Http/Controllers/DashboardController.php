<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Feedback;
use App\Models\Hairstyle;
use App\Models\Product;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $user = Auth::user();

    // Jika admin atau pegawai
    if ($user->hasRole(['admin', 'pegawai'])) {
        // Data chart dan statistik
        $totalCustomers = User::role('pelanggan')->count();
        $totalBookings = Booking::count();
        $todayBookings = Booking::whereDate('date_time', today())->count();
        $pendingBookings = Booking::whereDate('date_time', today())->where('status', 'pending')->count();
        $totalRevenue = Transaction::where('transaction_status', 'settlement')
            ->whereDate('created_at', Carbon::today())
            ->sum('gross_amount');

        // ➕ Total pelanggan hari ini (yang baru daftar)
        $todayCustomers = User::role('pelanggan')
            ->whereDate('created_at', today())
            ->count();

        // ➕ Total transaksi hari ini (status settlement)
        $todayTransactions = Transaction::where('transaction_status', 'settlement')
            ->whereDate('created_at', today())
            ->count();

        // Booking status stats
        $bookingStats = Booking::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Most popular service
        $popularService = Booking::select('service_id', \DB::raw('count(*) as count'))
            ->groupBy('service_id')
            ->orderByDesc('count')
            ->first();

        $popularServiceName = $popularService ? Service::find($popularService->service_id)->name : '-';
        $popularServiceCount = $popularService ? $popularService->count : 0;

        // User activity (registrations per month, last 6 months)
        $userActivity = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = User::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $userActivity->push([
                'month' => $month->translatedFormat('M y'),
                'count' => $count,
            ]);
        }

        // Revenue per month (last 6 months)
        $monthLabels = [];
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabels[] = $month->translatedFormat('M y');

            $monthlyRevenue = Transaction::where('transaction_status', 'settlement')
                ->whereYear('transaction_time', $month->year)
                ->whereMonth('transaction_time', $month->month)
                ->sum('gross_amount');

            $revenueData[] = $monthlyRevenue;
        }

        // Today's bookings with details (max 20 data)
        $todayBookingsData = Booking::with(['user', 'service'])
            ->whereDate('date_time', today())
            ->orderBy('date_time', 'asc')
            ->take(20)
            ->get();

        // Additional data for analytics section
        $dailyRevenue = Transaction::where('transaction_status', 'settlement')
            ->whereDate('created_at', today())
            ->sum('gross_amount');

        $monthlyRevenue = Transaction::where('transaction_status', 'settlement')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('gross_amount');

        $newCustomers = User::role('pelanggan')
            ->whereDate('created_at', today())
            ->count();

        $totalCustomersCount = User::role('pelanggan')->count();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalBookings',
            'todayBookings',
            'pendingBookings',
            'totalRevenue',
            'todayCustomers',     // ➕ total pelanggan hari ini
            'todayTransactions',  // ➕ total transaksi hari ini
            'bookingStats',
            'popularServiceName',
            'popularServiceCount',
            'userActivity',
            'monthLabels',
            'revenueData',
            'todayBookingsData',
            'dailyRevenue',
            'monthlyRevenue',
            'newCustomers',
            'totalCustomersCount'
        ));
    }

    // Jika bukan admin atau pegawai, tampilkan dashboard user biasa
    $services = Service::all();
    $hairstyles = Hairstyle::all();
    $users = User::all();
    $products = Product::active()->take(6)->get();
    $testimonials = Feedback::active()
        ->public()
        ->with(['user', 'booking'])
        ->orderBy('created_at', 'desc')
        ->take(6)
        ->get();

    return view('dashboard', compact('services', 'hairstyles', 'users', 'products', 'testimonials'));
}


    /**
     * Get dashboard data for AJAX refresh
     */
    public function getDashboardData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $user = Auth::user();
        
        if (!$user->hasRole(['admin', 'pegawai'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get fresh data
        $totalCustomers = User::role('pelanggan')->count();
        $totalBookings = Booking::count();
        $todayBookings = Booking::whereDate('date_time', today())->count();
        $pendingBookings = Booking::whereDate('date_time', today())->where('status', 'pending')->count();
        $totalRevenue = Transaction::where('transaction_status', 'settlement')
            ->whereDate('created_at', Carbon::today())
            ->sum('gross_amount');

        // Most popular service
        $popularService = Booking::select('service_id', \DB::raw('count(*) as count'))
            ->groupBy('service_id')
            ->orderByDesc('count')
            ->first();

        $popularServiceName = $popularService ? Service::find($popularService->service_id)->name : '-';

        // Today's bookings with details (max 20 data)
        $todayBookingsData = Booking::with(['user', 'service'])
            ->whereDate('date_time', today())
            ->orderBy('date_time', 'asc')
            ->take(20)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'customer_name' => $booking->user->name ?? 'N/A',
                    'service_name' => $booking->service->name ?? 'N/A',
                    'time' => Carbon::parse($booking->date_time)->format('H:i'),
                    'status' => $booking->status,
                    'status_badge' => $this->getStatusBadge($booking->status),
                    'total_price' => 'Rp' . number_format($booking->total_price, 0, ',', '.')
                ];
            });

        return response()->json([
            'totalCustomers' => $totalCustomers,
            'totalBookings' => $totalBookings,
            'todayBookings' => $todayBookings,
            'pendingBookings' => $pendingBookings,
            'totalRevenue' => 'Rp' . number_format($totalRevenue, 0, ',', '.'),
            'popularServiceName' => $popularServiceName,
            'todayBookingsData' => $todayBookingsData,
            'lastUpdate' => now()->format('H:i:s')
        ]);
    }

    /**
     * Get status badge HTML
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'confirmed' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Confirmed</span>',
            'completed' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Completed</span>',
            'cancelled' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Cancelled</span>',
        ];

        return $badges[$status] ?? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">' . ucfirst($status) . '</span>';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }

    /**
     * Export reports with date filter
     */
    public function exportReport(Request $request)
    {
        try {
            $type = $request->get('type');
            $month = $request->get('month');
            $year = $request->get('year');
            $format = $request->get('format', 'pdf'); // pdf, excel, csv

            // Debug logging
            \Log::info('Export Request:', [
                'type' => $type,
                'month' => $month,
                'year' => $year,
                'format' => $format
            ]);

            if (!$type) {
                return response()->json(['error' => 'Type parameter is required'], 400);
            }

            $filename = $this->generateFilename($type, $month, $year, $format);

            switch ($type) {
                case 'financial':
                    return $this->exportFinancial($month, $year, $format, $filename);
                case 'bookings':
                    return $this->exportBookings($month, $year, $format, $filename);
                case 'customers':
                    return $this->exportCustomers($month, $year, $format, $filename);
                default:
                    return response()->json(['error' => 'Invalid export type: ' . $type], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Export Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateFilename($type, $month, $year, $format)
    {
        $period = '';
        if ($month && $year) {
            $period = '_' . Carbon::create($year, $month)->format('M_Y');
        } elseif ($year) {
            $period = '_' . $year;
        } else {
            $period = '_' . Carbon::now()->format('M_Y');
        }

        $extension = $format === 'excel' ? 'xlsx' : ($format === 'csv' ? 'csv' : 'pdf');
        return ucfirst($type) . '_Report' . $period . '.' . $extension;
    }

    private function exportFinancial($month, $year, $format, $filename)
    {
        try {
            $query = Transaction::where('transaction_status', 'settlement')
                ->with(['booking.user', 'booking.service']);

            if ($month && $year) {
                $query->whereYear('created_at', $year)
                      ->whereMonth('created_at', $month);
            } elseif ($year) {
                $query->whereYear('created_at', $year);
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();
            
            \Log::info('Financial Export Data:', [
                'transactions_count' => $transactions->count(),
                'month' => $month,
                'year' => $year,
                'format' => $format
            ]);

            $totalRevenue = $transactions->sum('gross_amount');

            if ($format === 'pdf') {
                $pdf = Pdf::loadView('exports.financial-pdf', compact('transactions', 'totalRevenue', 'month', 'year'));
                return $pdf->download($filename);
            }

            // For Excel/CSV
            return Excel::download(new \App\Exports\FinancialExport($transactions, $month, $year), $filename);
            
        } catch (\Exception $e) {
            \Log::error('Export Financial Error:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    private function exportBookings($month, $year, $format, $filename)
    {
        try {
            $query = Booking::with(['user', 'service']);

            if ($month && $year) {
                $query->whereYear('date_time', $year)
                      ->whereMonth('date_time', $month);
            } elseif ($year) {
                $query->whereYear('date_time', $year);
            }

            $bookings = $query->orderBy('date_time', 'desc')->get();
            
            \Log::info('Bookings Export Data:', [
                'bookings_count' => $bookings->count(),
                'month' => $month,
                'year' => $year,
                'format' => $format
            ]);

            if ($format === 'pdf') {
                $pdf = Pdf::loadView('exports.bookings-pdf', compact('bookings', 'month', 'year'));
                return $pdf->download($filename);
            }

            return Excel::download(new \App\Exports\BookingsExport($bookings, $month, $year), $filename);
            
        } catch (\Exception $e) {
            \Log::error('Export Bookings Error:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    private function exportCustomers($month, $year, $format, $filename)
    {
        try {
            $query = User::role('pelanggan')->with(['bookings']);

            if ($month && $year) {
                $query->whereYear('created_at', $year)
                      ->whereMonth('created_at', $month);
            } elseif ($year) {
                $query->whereYear('created_at', $year);
            }

            $customers = $query->orderBy('created_at', 'desc')->get();
            
            \Log::info('Customers Export Data:', [
                'customers_count' => $customers->count(),
                'month' => $month,
                'year' => $year,
                'format' => $format
            ]);

            if ($format === 'pdf') {
                $pdf = Pdf::loadView('exports.customers-pdf', compact('customers', 'month', 'year'));
                return $pdf->download($filename);
            }

            return Excel::download(new \App\Exports\CustomersExport($customers, $month, $year), $filename);
            
        } catch (\Exception $e) {
            \Log::error('Export Customers Error:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    /**
     * Display welcome page with dynamic data
     */
    public function welcome()
    {
        $services = Service::all();
        $hairstyles = Hairstyle::all();
        $products = Product::active()->take(6)->get();
        $testimonials = Feedback::active()
            ->public()
            ->with(['user', 'booking'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('welcome', compact('services', 'hairstyles', 'products', 'testimonials'));
    }
}
