<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Hairstyle;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    const CACHE_TTL = 3600; // 1 hour

    const SERVICES_CACHE_KEY = 'active_services';

    const HAIRSTYLES_CACHE_KEY = 'active_hairstyles';

    const DAILY_BOOKINGS_KEY = 'daily_bookings_';

    const QUEUE_STATUS_KEY = 'queue_status_';

    /**
     * Get active services with caching
     */
    public function getActiveServices(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember(self::SERVICES_CACHE_KEY, self::CACHE_TTL, function () {
            return Service::where('is_active', true)
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Get active hairstyles with caching
     */
    public function getActiveHairstyles(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember(self::HAIRSTYLES_CACHE_KEY, self::CACHE_TTL, function () {
            return Hairstyle::orderBy('name')
                ->get();
        });
    }

    /**
     * Get daily bookings with caching
     */
    public function getDailyBookings(Carbon $date): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = self::DAILY_BOOKINGS_KEY.$date->format('Y-m-d');

        return Cache::remember($cacheKey, 1800, function () use ($date) { // 30 minutes
            return Booking::with(['user', 'service', 'hairstyle'])
                ->whereDate('date_time', $date->format('Y-m-d'))
                ->orderBy('queue_number')
                ->get();
        });
    }

    /**
     * Get queue status with caching
     */
    public function getQueueStatus(Carbon $date): array
    {
        $cacheKey = self::QUEUE_STATUS_KEY.$date->format('Y-m-d');

        return Cache::remember($cacheKey, 300, function () use ($date) { // 5 minutes
            $queueService = new QueueService;

            return $queueService->getQueueStatus($date);
        });
    }

    /**
     * Clear services cache
     */
    public function clearServicesCache(): void
    {
        Cache::forget(self::SERVICES_CACHE_KEY);
    }

    /**
     * Clear hairstyles cache
     */
    public function clearHairstylesCache(): void
    {
        Cache::forget(self::HAIRSTYLES_CACHE_KEY);
    }

    /**
     * Clear daily bookings cache
     */
    public function clearDailyBookingsCache(Carbon $date): void
    {
        $cacheKey = self::DAILY_BOOKINGS_KEY.$date->format('Y-m-d');
        Cache::forget($cacheKey);
    }

    /**
     * Clear queue status cache
     */
    public function clearQueueStatusCache(Carbon $date): void
    {
        $cacheKey = self::QUEUE_STATUS_KEY.$date->format('Y-m-d');
        Cache::forget($cacheKey);
    }

    /**
     * Clear all booking-related cache for a date
     */
    public function clearBookingCaches(Carbon $date): void
    {
        $this->clearDailyBookingsCache($date);
        $this->clearQueueStatusCache($date);
    }

    /**
     * Warm up cache for the next 7 days
     */
    public function warmUpCache(): void
    {
        // Warm up services and hairstyles
        $this->getActiveServices();
        $this->getActiveHairstyles();

        // Warm up daily bookings for next 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->addDays($i);
            $this->getDailyBookings($date);
            $this->getQueueStatus($date);
        }
    }

    /**
     * Cache statistics for dashboard
     */
    public function getDashboardStats(): array
    {
        return Cache::remember('dashboard_stats', 1800, function () {
            $today = Carbon::today();
            $thisMonth = Carbon::now()->startOfMonth();

            return [
                'today_bookings' => Booking::whereDate('date_time', $today)->count(),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
                'completed_today' => Booking::whereDate('date_time', $today)
                    ->where('status', 'completed')->count(),
                'monthly_revenue' => Booking::where('created_at', '>=', $thisMonth)
                    ->where('status', 'completed')
                    ->sum('total_price'),
                'active_services' => Service::where('is_active', true)->count(),
                'total_customers' => \App\Models\User::role('pelanggan')->count(),
            ];
        });
    }

    /**
     * Clear dashboard stats cache
     */
    public function clearDashboardStats(): void
    {
        Cache::forget('dashboard_stats');
    }
}
