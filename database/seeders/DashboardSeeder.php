<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Dashboard;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->generateDailyMetrics();
        $this->generateWeeklyMetrics();
        $this->generateMonthlyMetrics();

        $this->command->info('Dashboard metrics generated successfully');
    }

    private function generateDailyMetrics(): void
    {
        // Generate metrics for the past 30 days
        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            // Total bookings for this date
            $totalBookings = Booking::whereDate('created_at', $date)->count();
            Dashboard::firstOrCreate(
                ['metric_name' => 'total_bookings', 'date' => $date, 'period' => 'daily'],
                ['metric_value' => $totalBookings, 'metric_type' => 'count']
            );

            // Completed bookings
            $completedBookings = Booking::whereDate('created_at', $date)
                ->where('status', 'completed')->count();
            Dashboard::updateOrCreate(
                ['metric_name' => 'completed_bookings', 'date' => $date, 'period' => 'daily'],
                ['metric_value' => $completedBookings, 'metric_type' => 'count']
            );

            // Revenue
            $revenue = Transaction::whereDate('created_at', $date)
                ->where('payment_status', 'settlement')
                ->sum('total_amount');
            Dashboard::updateOrCreate(
                ['metric_name' => 'daily_revenue', 'date' => $date, 'period' => 'daily'],
                ['metric_value' => $revenue, 'metric_type' => 'currency']
            );

            // New customers
            $newCustomers = User::whereDate('created_at', $date)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'customer');
                })
                ->count();
            Dashboard::updateOrCreate(
                ['metric_name' => 'new_customers', 'date' => $date, 'period' => 'daily'],
                ['metric_value' => $newCustomers, 'metric_type' => 'count']
            );

            // Cancellation rate
            $cancelledBookings = Booking::whereDate('created_at', $date)
                ->where('status', 'cancelled')->count();
            $cancellationRate = $totalBookings > 0 ? round(($cancelledBookings / $totalBookings) * 100, 2) : 0;
            Dashboard::updateOrCreate(
                ['metric_name' => 'cancellation_rate', 'date' => $date, 'period' => 'daily'],
                ['metric_value' => $cancellationRate, 'metric_type' => 'percentage']
            );

            // Popular services
            $popularServices = Booking::whereDate('bookings.created_at', $date)
                ->join('services', 'bookings.service_id', '=', 'services.id')
                ->selectRaw('services.name, COUNT(*) as booking_count')
                ->groupBy('services.id', 'services.name')
                ->orderByDesc('booking_count')
                ->limit(3)
                ->pluck('booking_count', 'name')
                ->toArray();

            Dashboard::updateOrCreate(
                ['metric_name' => 'popular_services', 'date' => $date, 'period' => 'daily'],
                [
                    'metric_value' => json_encode($popularServices),
                    'metric_type' => 'json',
                    'additional_data' => $popularServices,
                ]
            );
        }
    }

    private function generateWeeklyMetrics(): void
    {
        // Generate weekly metrics for the past 12 weeks
        for ($i = 12; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();

            // Weekly bookings
            $weeklyBookings = Booking::whereBetween('created_at', [$weekStart, $weekEnd])->count();
            Dashboard::updateOrCreate(
                ['metric_name' => 'weekly_bookings', 'date' => $weekStart->toDateString(), 'period' => 'weekly'],
                ['metric_value' => $weeklyBookings, 'metric_type' => 'count']
            );

            // Weekly revenue
            $weeklyRevenue = Transaction::whereBetween('created_at', [$weekStart, $weekEnd])
                ->where('payment_status', 'settlement')
                ->sum('total_amount');
            Dashboard::updateOrCreate(
                ['metric_name' => 'weekly_revenue', 'date' => $weekStart->toDateString(), 'period' => 'weekly'],
                ['metric_value' => $weeklyRevenue, 'metric_type' => 'currency']
            );

            // Customer retention rate
            $repeatCustomers = Booking::whereBetween('created_at', [$weekStart, $weekEnd])
                ->selectRaw('user_id, COUNT(*) as booking_count')
                ->groupBy('user_id')
                ->having('booking_count', '>', 1)
                ->count();

            $totalCustomers = Booking::whereBetween('created_at', [$weekStart, $weekEnd])
                ->distinct('user_id')
                ->count();

            $retentionRate = $totalCustomers > 0 ? round(($repeatCustomers / $totalCustomers) * 100, 2) : 0;
            Dashboard::updateOrCreate(
                ['metric_name' => 'customer_retention_rate', 'date' => $weekStart->toDateString(), 'period' => 'weekly'],
                ['metric_value' => $retentionRate, 'metric_type' => 'percentage']
            );
        }
    }

    private function generateMonthlyMetrics(): void
    {
        // Generate monthly metrics for the past 6 months
        for ($i = 6; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();

            // Monthly bookings
            $monthlyBookings = Booking::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            Dashboard::updateOrCreate(
                ['metric_name' => 'monthly_bookings', 'date' => $monthStart->toDateString(), 'period' => 'monthly'],
                ['metric_value' => $monthlyBookings, 'metric_type' => 'count']
            );

            // Monthly revenue
            $monthlyRevenue = Transaction::whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('payment_status', 'settlement')
                ->sum('total_amount');
            Dashboard::updateOrCreate(
                ['metric_name' => 'monthly_revenue', 'date' => $monthStart->toDateString(), 'period' => 'monthly'],
                ['metric_value' => $monthlyRevenue, 'metric_type' => 'currency']
            );

            // Average order value
            $totalTransactions = Transaction::whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('payment_status', 'settlement')
                ->count();

            $avgOrderValue = $totalTransactions > 0 ? round($monthlyRevenue / $totalTransactions, 0) : 0;
            Dashboard::updateOrCreate(
                ['metric_name' => 'avg_order_value', 'date' => $monthStart->toDateString(), 'period' => 'monthly'],
                ['metric_value' => $avgOrderValue, 'metric_type' => 'currency']
            );

            // Service performance
            $servicePerformance = Booking::whereBetween('bookings.created_at', [$monthStart, $monthEnd])
                ->join('services', 'bookings.service_id', '=', 'services.id')
                ->selectRaw('services.name, COUNT(*) as booking_count, SUM(services.price) as total_revenue')
                ->groupBy('services.id', 'services.name')
                ->orderByDesc('total_revenue')
                ->get()
                ->toArray();

            Dashboard::updateOrCreate(
                ['metric_name' => 'service_performance', 'date' => $monthStart->toDateString(), 'period' => 'monthly'],
                [
                    'metric_value' => json_encode($servicePerformance),
                    'metric_type' => 'json',
                    'additional_data' => $servicePerformance,
                ]
            );
        }
    }
}
