<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hairstyle;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            return view('admin.dashboard', compact(
                'totalCustomers',
                'totalBookings',
                'todayBookings',
                'pendingBookings',
                'totalRevenue',
                'bookingStats',
                'popularServiceName',
                'popularServiceCount',
                'userActivity',
                'monthLabels',        // chart label bulan
                'revenueData'         // chart data revenue
            ));
        }

        // Jika bukan admin atau pegawai, tampilkan dashboard user biasa
        $services = Service::all();
        $hairstyles = Hairstyle::all();
        $users = User::all();

        return view('dashboard', compact('services', 'hairstyles', 'users'));
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
}
