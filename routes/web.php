<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\TheaterController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', [PublicController::class, 'index'])->name('home');

// Movie listings for public
Route::get('/movies', [PublicController::class, 'movies'])->name('public.movies');
Route::get('/movies/{movie}', [PublicController::class, 'movieDetail'])->name('public.movies.show');

// Theater information
Route::get('/theaters', [PublicController::class, 'theaters'])->name('public.theaters');

// Showtimes & Booking (public)
Route::get('/showtimes', [PublicController::class, 'showtimes'])->name('public.showtimes');
Route::get('/book/{showtime}', [PublicController::class, 'bookingForm'])->name('public.booking.form');

// Additional public pages
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::get('/faq', [PublicController::class, 'faq'])->name('public.faq');


Route::post('/login-direct', function (Request $request) {
    return redirect('/admin');
})->name('login.direct');


Route::post('/signup-direct', function (Request $request) {
    return redirect('/admin');
})->name('signup.direct');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Authentication)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::post('/book/{showtime}', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('booking.my');
    Route::get('/booking/{booking}/ticket', [BookingController::class, 'downloadTicket'])->name('booking.ticket');

    Route::get('/debug-user', function () {
        $user = Auth::user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames() : 'No role method',
        ]);
    });
});


    Route::prefix('admin')->group(function () {


    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Movies Management
    Route::resource('movies', MovieController::class);
    Route::post('movies/bulk-delete', [MovieController::class, 'bulkDelete'])->name('movies.bulk-delete');
    Route::post('movies/{movie}/upload-poster', [MovieController::class, 'uploadPoster'])->name('movies.upload-poster');

    // Theaters Management
    Route::resource('theaters', TheaterController::class);
    Route::get('theaters/{theater}/seats', [TheaterController::class, 'seats'])->name('theaters.seats');
    Route::post('theaters/{theater}/seats', [TheaterController::class, 'updateSeats'])->name('theaters.update-seats');

    // Showtimes Management
    Route::resource('showtimes', ShowtimeController::class);
    Route::post('showtimes/bulk-create', [ShowtimeController::class, 'bulkCreate'])->name('showtimes.bulk-create');
    Route::get('showtimes/{showtime}/seats', [ShowtimeController::class, 'seats'])->name('showtimes.seats');

    // Bookings Management
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::post('bookings/create-manual', [BookingController::class, 'createManual'])->name('bookings.create-manual');

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/movies', [ReportController::class, 'movies'])->name('reports.movies');
        Route::get('/theaters', [ReportController::class, 'theaters'])->name('reports.theaters');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    });

    // User Management
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::patch('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');

    // System Settings
    Route::get('settings', [DashboardController::class, 'settings'])->name('admin.settings');
    Route::post('settings', [DashboardController::class, 'updateSettings'])->name('admin.settings.update');
    Route::get('logs', [DashboardController::class, 'logs'])->name('admin.logs');
    Route::post('cache-clear', [DashboardController::class, 'clearCache'])->name('admin.cache.clear');
});



Route::get('/api/theater-capacity/{theater}', [ShowtimeController::class, 'getTheaterCapacity']);
Route::get('/api/showtimes/{showtime}/available-seats', [ShowtimeController::class, 'getAvailableSeats']);
Route::get('/api/movies/{movie}/showtimes', [PublicController::class, 'getMovieShowtimes']);
Route::get('/api/search', [PublicController::class, 'search'])->name('api.search');
Route::get('/api/theaters/{theater}', [PublicController::class, 'getTheaterInfo']);
Route::get('/api/popular-movies', [PublicController::class, 'getPopularMovies']);
Route::get('/api/movie-schedule', [PublicController::class, 'getMovieSchedule']);
