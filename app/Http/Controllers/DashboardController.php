<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Theater;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_movies' => Movie::count(),
            'active_theaters' => Theater::where('is_active', true)->count(),
            'now_showing' => Movie::where('status', 'now_showing')->count(),
            'coming_soon' => Movie::where('status', 'coming_soon')->count(),
            'total_users' => User::count(),
            'today_showtimes' => Showtime::whereDate('show_date', today())->count(),
            'total_bookings' => class_exists('App\Models\Booking') ? \App\Models\Booking::count() : 0,
            'today_revenue' => class_exists('App\Models\Booking') ? \App\Models\Booking::whereDate('created_at', today())->sum('total_amount') : 0,
        ];

        // Recent activities
        $recent_movies = Movie::latest()->take(5)->get();
        $recent_bookings = class_exists('App\Models\Booking') 
            ? \App\Models\Booking::with(['showtime.movie', 'user'])->latest()->take(10)->get() 
            : collect();

        // Monthly revenue chart data
        $monthly_revenue = $this->getMonthlyRevenue();
        
        // Popular genres
        $popular_genres = $this->getPopularGenres();

        // Theater utilization
        $theater_stats = $this->getTheaterStats();

        return view('admin.dashboard', compact(
            'stats',
            'recent_movies',
            'recent_bookings',
            'monthly_revenue',
            'popular_genres',
            'theater_stats'
        ));
    }

    public function settings()
    {
        $settings = [
            'site_name' => config('app.name'),
            'timezone' => config('app.timezone'),
            'currency' => 'USD',
            'booking_advance_days' => 30,
            'max_seats_per_booking' => 10,
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'timezone' => 'required|string',
            'currency' => 'required|string|max:3',
            'booking_advance_days' => 'required|integer|min:1|max:365',
            'max_seats_per_booking' => 'required|integer|min:1|max:50',
        ]);

        // Update settings (you might want to store these in a settings table)
        foreach ($validated as $key => $value) {
            Cache::put("setting.{$key}", $value, now()->addYear());
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully.');
    }

    public function logs()
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logFile)) {
            $content = file_get_contents($logFile);
            $lines = array_reverse(explode("\n", $content));
            $logs = array_slice($lines, 0, 100); // Get last 100 lines
        }

        return view('admin.logs', compact('logs'));
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return redirect()->back()
                ->with('success', 'Cache cleared successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    private function getMonthlyRevenue()
    {
        if (!class_exists('App\Models\Booking')) {
            return collect();
        }

        return \App\Models\Booking::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }

    private function getPopularGenres()
    {
        return Movie::select('genre', DB::raw('COUNT(*) as count'))
            ->groupBy('genre')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();
    }

    private function getTheaterStats()
    {
        return Theater::withCount(['showtimes' => function ($query) {
                $query->whereDate('show_date', '>=', today());
            }])
            ->get()
            ->map(function ($theater) {
                $utilization = $theater->showtimes_count > 0 
                    ? ($theater->showtimes_count / 10) * 100 // Assuming 10 shows per day max
                    : 0;
                
                return [
                    'name' => $theater->name,
                    'type' => $theater->type,
                    'capacity' => $theater->capacity,
                    'utilization' => min($utilization, 100)
                ];
            });
    }
}
