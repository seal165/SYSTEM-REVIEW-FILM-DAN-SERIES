<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Theater;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicController extends Controller
{
    /**
     * Display the home page
     */
    public function index(): View
    {
        // Get featured movies (now showing)
        $featuredMovies = Movie::where('status', 'now_showing')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Get coming soon movies
        $comingSoonMovies = Movie::where('status', 'coming_soon')
            ->orderBy('release_date', 'asc')
            ->take(4)
            ->get();

        // Get today's showtimes
        $todayShowtimes = Showtime::with(['movie', 'theater'])
            ->whereDate('show_date', today())
            ->where('show_time', '>', now())
            ->orderBy('show_time')
            ->take(8)
            ->get();

        // Get statistics for hero section
        $stats = [
            'total_movies' => Movie::where('status', 'now_showing')->count(),
            'total_theaters' => Theater::where('is_active', true)->count(),
            'today_shows' => Showtime::whereDate('show_date', today())->count(),
        ];

        return view('public.home', compact(
            'featuredMovies',
            'comingSoonMovies',
            'todayShowtimes',
            'stats'
        ));
    }

    /**
     * Display all movies for public
     */
    public function movies(Request $request): View
    {
        $query = Movie::query();

        // Filter by status
        $status = $request->get('status', 'now_showing');
        if (in_array($status, ['now_showing', 'coming_soon', 'ended'])) {
            $query->where('status', $status);
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Sort options
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'release_date':
                $query->orderBy('release_date', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $movies = $query->paginate(12);

        // Get available genres for filter
        $genres = Movie::select('genre')
            ->distinct()
            ->whereNotNull('genre')
            ->pluck('genre')
            ->sort();

        return view('public.movies', compact('movies', 'genres', 'status', 'sortBy'));
    }

    /**
     * Display movie detail
     */
    public function movieDetail(Movie $movie): View
    {
        // Get upcoming showtimes for this movie
        $showtimes = Showtime::with('theater')
            ->where('movie_id', $movie->id)
            ->where('show_date', '>=', today())
            ->orderBy('show_date')
            ->orderBy('show_time')
            ->get()
            ->groupBy('show_date');

        // Get related movies (same genre)
        $relatedMovies = Movie::where('genre', $movie->genre)
            ->where('id', '!=', $movie->id)
            ->where('status', 'now_showing')
            ->take(4)
            ->get();

        return view('public.movie-detail', compact('movie', 'showtimes', 'relatedMovies'));
    }

    /**
     * Display theaters information
     */
    public function theaters(): View
    {
        $theaters = Theater::where('is_active', true)
            ->withCount(['showtimes' => function ($query) {
                $query->whereDate('show_date', '>=', today());
            }])
            ->get();

        return view('public.theaters', compact('theaters'));
    }

    /**
     * Display showtimes
     */
    public function showtimes(Request $request): View
    {
        $query = Showtime::with(['movie', 'theater']);

        // Filter by date (default to today)
        $selectedDate = $request->get('date', today()->format('Y-m-d'));
        $query->whereDate('show_date', $selectedDate);

        // Filter by movie
        if ($request->filled('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        // Filter by theater
        if ($request->filled('theater_id')) {
            $query->where('theater_id', $request->theater_id);
        }

        // Only show future showtimes for today
        if ($selectedDate === today()->format('Y-m-d')) {
            $query->where('show_time', '>', now());
        }

        $showtimes = $query->orderBy('show_time')->get();

        // Get available movies and theaters for filters
        $movies = Movie::where('status', 'now_showing')->orderBy('title')->get();
        $theaters = Theater::where('is_active', true)->orderBy('name')->get();

        // Generate date options (next 7 days)
        $dateOptions = collect();
        for ($i = 0; $i < 7; $i++) {
            $date = today()->addDays($i);
            $dateOptions->push([
                'value' => $date->format('Y-m-d'),
                'label' => $date->format('l, M d'),
                'is_today' => $date->isToday(),
            ]);
        }

        return view('public.showtimes', compact(
            'showtimes',
            'movies',
            'theaters',
            'dateOptions',
            'selectedDate'
        ));
    }

    /**
     * Display booking form
     */
    public function bookingForm(Showtime $showtime): View
    {
        // Check if showtime is in the future
        if ($showtime->show_time <= now()) {
            abort(404, 'This showtime is no longer available for booking.');
        }

        // Check if seats are available
        if ($showtime->available_seats <= 0) {
            abort(404, 'No seats available for this showtime.');
        }

        $showtime->load(['movie', 'theater']);

        return view('public.booking-form', compact('showtime'));
    }

    /**
     * Search functionality
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json([
                'movies' => [],
                'theaters' => [],
                'showtimes' => []
            ]);
        }

        // Search movies
        $movies = Movie::where('title', 'like', "%{$query}%")
            ->orWhere('genre', 'like', "%{$query}%")
            ->orWhere('director', 'like', "%{$query}%")
            ->take(5)
            ->get(['id', 'title', 'genre', 'poster_url']);

        // Search theaters
        $theaters = Theater::where('name', 'like', "%{$query}%")
            ->orWhere('location', 'like', "%{$query}%")
            ->where('is_active', true)
            ->take(3)
            ->get(['id', 'name', 'location', 'type']);

        // Search showtimes (by movie title)
        $showtimes = Showtime::with(['movie', 'theater'])
            ->whereHas('movie', function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%");
            })
            ->whereDate('show_date', '>=', today())
            ->take(5)
            ->get();

        return response()->json([
            'movies' => $movies,
            'theaters' => $theaters,
            'showtimes' => $showtimes
        ]);
    }

    /**
     * Get movie showtimes for AJAX
     */
    public function getMovieShowtimes(Movie $movie, Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        
        $showtimes = Showtime::with('theater')
            ->where('movie_id', $movie->id)
            ->whereDate('show_date', $date)
            ->orderBy('show_time')
            ->get();

        return response()->json($showtimes);
    }

    /**
     * Get theater information
     */
    public function getTheaterInfo(Theater $theater)
    {
        $theater->load(['showtimes' => function ($query) {
            $query->with('movie')
                ->whereDate('show_date', '>=', today())
                ->orderBy('show_date')
                ->orderBy('show_time');
        }]);

        return response()->json($theater);
    }

    /**
     * Get popular movies
     */
    public function getPopularMovies()
    {
        $popularMovies = Movie::where('status', 'now_showing')
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get(['id', 'title', 'genre', 'rating', 'poster_url']);

        return response()->json($popularMovies);
    }

    /**
     * Get movie schedule for a specific date
     */
    public function getMovieSchedule(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        
        $schedule = Movie::with(['showtimes' => function ($query) use ($date) {
                $query->with('theater')
                    ->whereDate('show_date', $date)
                    ->orderBy('show_time');
            }])
            ->where('status', 'now_showing')
            ->get();

        return response()->json($schedule);
    }

    /**
     * Contact page
     */
    public function contact(): View
    {
        return view('public.contact');
    }

    /**
     * About page
     */
    public function about(): View
    {
        $stats = [
            'total_movies' => Movie::count(),
            'total_theaters' => Theater::count(),
            'years_operating' => 5, // You can make this dynamic
            'happy_customers' => class_exists('App\Models\Booking') ? \App\Models\Booking::distinct('user_id')->count() : 1000,
        ];

        return view('public.about', compact('stats'));
    }

    /**
     * FAQ page
     */
    public function faq(): View
    {
        $faqs = [
            [
                'question' => 'How do I book tickets online?',
                'answer' => 'You can book tickets by selecting a movie, choosing a showtime, and following the booking process. You\'ll need to create an account or log in to complete your booking.'
            ],
            [
                'question' => 'Can I cancel my booking?',
                'answer' => 'Yes, you can cancel your booking up to 2 hours before the showtime. Please contact our customer service or use your account dashboard to cancel.'
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept cash at the counter, credit/debit cards, and online payments through our secure payment gateway.'
            ],
            [
                'question' => 'How early should I arrive at the theater?',
                'answer' => 'We recommend arriving at least 15-30 minutes before the showtime to collect your tickets and find your seats.'
            ],
            [
                'question' => 'Do you offer group discounts?',
                'answer' => 'Yes, we offer special rates for groups of 10 or more. Please contact our customer service for group booking inquiries.'
            ],
        ];

        return view('public.faq', compact('faqs'));
    }
}