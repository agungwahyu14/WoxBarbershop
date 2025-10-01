<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display reports dashboard
     */
    public function index()
    {
        $data = [
            'totalBookings' => Booking::count(),
            'totalRevenue' => Transaction::where('transaction_status', 'settlement')->sum('gross_amount'),
            'totalCustomers' => User::role('pelanggan')->count(),
            'activeServices' => Service::where('is_active', true)->count(),
        ];

        return view('admin.reports.index', $data);
    }

    /**
     * Financial reports
     */
    public function financial(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        // Revenue by period
        $revenueData = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(gross_amount) as total_revenue'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->where('transaction_status', 'settlement')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenue by payment method
        $paymentMethodData = Transaction::select('payment_type', DB::raw('SUM(gross_amount) as total'))
            ->where('transaction_status', 'settlement')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('payment_type')
            ->get();

        // Revenue by service
        $serviceRevenueData = DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->join('transactions', 'bookings.id', '=', 'transactions.order_id')
            ->select('services.name', DB::raw('SUM(transactions.gross_amount) as total_revenue'))
            ->where('transactions.transaction_status', 'settlement')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('services.id', 'services.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'revenueData' => $revenueData,
            'paymentMethodData' => $paymentMethodData,
            'serviceRevenueData' => $serviceRevenueData,
            'totalRevenue' => $revenueData->sum('total_revenue'),
            'totalTransactions' => $revenueData->sum('transaction_count'),
        ];

        return view('admin.reports.financial', $data);
    }

    /**
     * Booking reports
     */
    public function bookings(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        // Bookings by status
        $statusData = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        // Bookings by service
        $serviceData = DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('COUNT(bookings.id) as count'))
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('services.id', 'services.name')
            ->orderBy('count', 'desc')
            ->get();

        // Daily bookings
        $dailyBookings = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'statusData' => $statusData,
            'serviceData' => $serviceData,
            'dailyBookings' => $dailyBookings,
            'totalBookings' => $statusData->sum('count'),
        ];

        return view('admin.reports.bookings', $data);
    }

    /**
     * Customer reports
     */
    public function customers(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        // New customers by period
        $newCustomers = User::role('pelanggan')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top customers by booking count
        $topCustomers = DB::table('users')
            ->join('bookings', 'users.id', '=', 'bookings.user_id')
            ->select('users.name', 'users.email', DB::raw('COUNT(bookings.id) as booking_count'))
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('booking_count', 'desc')
            ->limit(10)
            ->get();

        // Customer retention rate
        $totalCustomers = User::role('pelanggan')->count();
        $activeCustomers = User::role('pelanggan')
            ->whereHas('bookings', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->count();

        $retentionRate = $totalCustomers > 0 ? round(($activeCustomers / $totalCustomers) * 100, 2) : 0;

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'newCustomers' => $newCustomers,
            'topCustomers' => $topCustomers,
            'totalCustomers' => $totalCustomers,
            'activeCustomers' => $activeCustomers,
            'retentionRate' => $retentionRate,
        ];

        return view('admin.reports.customers', $data);
    }

    /**
     * Export financial report
     */
    public function exportFinancial(Request $request)
    {
        // Implementation for export functionality
        // Can use Laravel Excel or custom CSV generation
        return response()->json(['message' => 'Export functionality will be implemented']);
    }
}
