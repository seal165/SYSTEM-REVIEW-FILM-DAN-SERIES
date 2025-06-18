<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Theater;
use App\Models\Showtime;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $summary = [
            'total_revenue' => $this->getTotalRevenue(),
            'total_tickets_sold' => $this->getTotalTicketsSold(),
            'average_ticket_price' => $this->getAverageTicketPrice(),
            'most_popular_movie' => $this->getMostPopularMovie(),
            'best_performing_theater' => $this->getBestPerformingTheater(),
        ];

        return view('admin.reports.index', compact('summary'));
    }

    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $groupBy = $request->get('group_by', 'day'); // day, week, month

        $salesData = $this->getSalesData($startDate, $endDate, $groupBy);
        $topMovies = $this->getTopMoviesBySales($startDate, $endDate);
        $theaterPerformance = $this->getTheaterPerformance($startDate, $endDate);

        return view('admin.reports.sales', compact(
            'salesData',
            'topMovies',
            'theaterPerformance',
            'startDate',
            'endDate',
            'groupBy'
        ));
    }

    public function movies(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $movieStats = $this->getMovieStatistics($startDate, $endDate);
        $genrePerformance = $this->getGenrePerformance($startDate, $endDate);
        $movieTrends = $this->getMovieTrends($startDate, $endDate);

        return view('admin.reports.movies', compact(
            'movieStats',
            'genrePerformance',
            'movieTrends',
            'startDate',
            'endDate'
        ));
    }

    public function theaters(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $theaterStats = $this->getTheaterStatistics($startDate, $endDate);
        $occupancyRates = $this->getOccupancyRates($startDate, $endDate);
        $revenueByTheater = $this->getRevenueByTheater($startDate, $endDate);

        return view('admin.reports.theaters', compact(
            'theaterStats',
            'occupancyRates',
            'revenueByTheater',
            'startDate',
            'endDate'
        ));
    }

    public function export(Request $request, $type)
    {
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        switch ($type) {
            case 'sales':
                return $this->exportSalesReport($startDate, $endDate);
            case 'movies':
                return $this->exportMoviesReport($startDate, $endDate);
            case 'theaters':
                return $this->exportTheatersReport($startDate, $endDate);
            default:
                abort(404);
        }
    }

    private function getTotalRevenue()
    {
        if (!class_exists('App\Models\Booking')) {
            return 0;
        }
        return Booking::sum('total_amount') ?? 0;
    }

    private function getTotalTicketsSold()
    {
        if (!class_exists('App\Models\Booking')) {
            return 0;
        }
        return Booking::sum('seats_booked') ?? 0;
    }

    private function getAverageTicketPrice()
    {
        if (!class_exists('App\Models\Booking')) {
            return 0;
        }
        
        $totalRevenue = Booking::sum('total_amount') ?? 0;
        $totalTickets = Booking::sum('seats_booked') ?? 0;
        
        return $totalTickets > 0 ? $totalRevenue / $totalTickets : 0;
    }

    private function getMostPopularMovie()
    {
        if (!class_exists('App\Models\Booking')) {
            return Movie::first();
        }

        $movieId = DB::table('bookings')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->select('showtimes.movie_id', DB::raw('SUM(bookings.seats_booked) as total_tickets'))
            ->groupBy('showtimes.movie_id')
            ->orderBy('total_tickets', 'desc')
            ->first()?->movie_id;

        return $movieId ? Movie::find($movieId) : Movie::first();
    }

    private function getBestPerformingTheater()
    {
        if (!class_exists('App\Models\Booking')) {
            return Theater::first();
        }

        $theaterId = DB::table('bookings')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->select('showtimes.theater_id', DB::raw('SUM(bookings.total_amount) as total_revenue'))
            ->groupBy('showtimes.theater_id')
            ->orderBy('total_revenue', 'desc')
            ->first()?->theater_id;

        return $theaterId ? Theater::find($theaterId) : Theater::first();
    }

    private function getSalesData($startDate, $endDate, $groupBy)
    {
        if (!class_exists('App\Models\Booking')) {
            return collect();
        }

        $dateFormat = match($groupBy) {
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            default => '%Y-%m-%d'
        };

        return Booking::select(
                DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('SUM(seats_booked) as tickets_sold'),
                DB::raw('COUNT(*) as bookings_count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    private function getTopMoviesBySales($startDate, $endDate)
    {
        if (!class_exists('App\Models\Booking')) {
            return collect();
        }

        return DB::table('bookings')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->select(
                'movies.title',
                'movies.genre',
                DB::raw('SUM(bookings.total_amount) as revenue'),
                DB::raw('SUM(bookings.seats_booked) as tickets_sold')
            )
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('movies.id', 'movies.title', 'movies.genre')
            ->orderBy('revenue', 'desc')
            ->take(10)
            ->get();
    }

    private function getTheaterPerformance($startDate, $endDate)
    {
        if (!class_exists('App\Models\Booking')) {
            return collect();
        }

        return DB::table('bookings')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('theaters', 'showtimes.theater_id', '=', 'theaters.id')
            ->select(
                'theaters.name',
                'theaters.type',
                'theaters.capacity',
                DB::raw('SUM(bookings.total_amount) as revenue'),
                DB::raw('SUM(bookings.seats_booked) as tickets_sold'),
                DB::raw('ROUND((SUM(bookings.seats_booked) / (theaters.capacity * COUNT(DISTINCT showtimes.id))) * 100, 2) as occupancy_rate')
            )
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('theaters.id', 'theaters.name', 'theaters.type', 'theaters.capacity')
            ->orderBy('revenue', 'desc')
            ->get();
    }

    private function getMovieStatistics($startDate, $endDate)
    {
        $query = Movie::select('movies.*')
            ->selectRaw('COALESCE(SUM(bookings.total_amount), 0) as revenue')
            ->selectRaw('COALESCE(SUM(bookings.seats_booked), 0) as tickets_sold')
            ->selectRaw('COUNT(DISTINCT showtimes.id) as total_showtimes')
            ->leftJoin('showtimes', 'movies.id', '=', 'showtimes.movie_id')
            ->leftJoin('bookings', function($join) use ($startDate, $endDate) {
                $join->on('showtimes.id', '=', 'bookings.showtime_id')
                     ->whereBetween('bookings.created_at', [$startDate, $endDate]);
            })
            ->groupBy('movies.id')
            ->orderBy('revenue', 'desc');

        return $query->get();
    }

    private function getGenrePerformance($startDate, $endDate)
    {
        if (!class_exists('App\Models\Booking')) {
            return Movie::select('genre', DB::raw('COUNT(*) as movie_count'))
                ->groupBy('genre')
                ->get();
        }

        return DB::table('movies')
            ->leftJoin('showtimes', 'movies.id', '=', 'showtimes.movie_id')
            ->leftJoin('bookings', function($join) use ($startDate, $endDate) {
                $join->on('showtimes.id', '=', 'bookings.showtime_id')
                     ->whereBetween('bookings.created_at', [$startDate, $endDate]);
            })
            ->select(
                'movies.genre',
                DB::raw('COUNT(DISTINCT movies.id) as movie_count'),
                DB::raw('COALESCE(SUM(bookings.total_amount), 0) as revenue'),
                DB::raw('COALESCE(SUM(bookings.seats_booked), 0) as tickets_sold')
            )
            ->groupBy('movies.genre')
            ->orderBy('revenue', 'desc')
            ->get();
    }

    private function getMovieTrends($startDate, $endDate)
    {
        if (!class_exists('App\Models\Booking')) {
            return collect();
        }

        return DB::table('bookings')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->select(
                'movies.title',
                DB::raw('DATE(bookings.created_at) as booking_date'),
                DB::raw('SUM(bookings.seats_booked) as daily_tickets')
            )
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('movies.id', 'movies.title', 'booking_date')
            ->orderBy('booking_date')
            ->get();
    }

    private function getTheaterStatistics($startDate, $endDate)
    {
        return $this->getTheaterPerformance($startDate, $endDate);
    }

    private function getOccupancyRates($startDate, $endDate)
    {
        if (!class_exists('App\Models\Booking')) {
            return Theater::select('name', 'capacity', DB::raw('0 as occupancy_rate'))->get();
        }

        return DB::table('theaters')
            ->leftJoin('showtimes', 'theaters.id', '=', 'showtimes.theater_id')
            ->leftJoin('bookings', function($join) use ($startDate, $endDate) {
                $join->on('showtimes.id', '=', 'bookings.showtime_id')
                     ->whereBetween('bookings.created_at', [$startDate, $endDate]);
            })
            ->select(
                'theaters.name',
                'theaters.capacity',
                DB::raw('COALESCE(ROUND((SUM(bookings.seats_booked) / (theaters.capacity * COUNT(DISTINCT showtimes.id))) * 100, 2), 0) as occupancy_rate')
            )
            ->groupBy('theaters.id', 'theaters.name', 'theaters.capacity')
            ->get();
    }

    private function getRevenueByTheater($startDate, $endDate)
    {
        return $this->getTheaterPerformance($startDate, $endDate);
    }

    private function exportSalesReport($startDate, $endDate)
    {
        $data = $this->getSalesData($startDate, $endDate, 'day');
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sales_report_' . $startDate . '_to_' . $endDate . '.csv"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Revenue', 'Tickets Sold', 'Bookings Count']);
            
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->period,
                    $row->revenue,
                    $row->tickets_sold,
                    $row->bookings_count
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportMoviesReport($startDate, $endDate)
    {
        $data = $this->getMovieStatistics($startDate, $endDate);
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="movies_report_' . $startDate . '_to_' . $endDate . '.csv"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Title', 'Genre', 'Revenue', 'Tickets Sold', 'Total Showtimes']);
            
            foreach ($data as $movie) {
                fputcsv($file, [
                    $movie->title,
                    $movie->genre,
                    $movie->revenue,
                    $movie->tickets_sold,
                    $movie->total_showtimes
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportTheatersReport($startDate, $endDate)
    {
        $data = $this->getTheaterStatistics($startDate, $endDate);
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="theaters_report_' . $startDate . '_to_' . $endDate . '.csv"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Theater Name', 'Type', 'Capacity', 'Revenue', 'Tickets Sold', 'Occupancy Rate']);
            
            foreach ($data as $theater) {
                fputcsv($file, [
                    $theater->name,
                    $theater->type,
                    $theater->capacity,
                    $theater->revenue,
                    $theater->tickets_sold,
                    $theater->occupancy_rate . '%'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}