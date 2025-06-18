@extends('layouts.public')

@section('title', 'Cinema - Book Your Movie Tickets Online')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-4">Experience Movies Like Never Before</h1>
        <p class="lead mb-5">Book your tickets online and enjoy the latest blockbusters in our premium theaters.</p>
        <div class="d-flex justify-content-center">
            <a href="{{ route('public.movies') }}" class="btn btn-primary btn-lg me-3">
                <i class="fas fa-film me-2"></i>Now Showing
            </a>
            <a href="{{ route('public.showtimes') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-ticket-alt me-2"></i>Book Tickets
            </a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="d-inline-block p-4 bg-white rounded-circle shadow mb-3">
                    <i class="fas fa-film fa-3x text-primary"></i>
                </div>
                <h2 class="h4 fw-bold">{{ $stats['total_movies'] }}</h2>
                <p class="text-muted">Movies Now Showing</p>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="d-inline-block p-4 bg-white rounded-circle shadow mb-3">
                    <i class="fas fa-couch fa-3x text-primary"></i>
                </div>
                <h2 class="h4 fw-bold">{{ $stats['total_theaters'] }}</h2>
                <p class="text-muted">Premium Theaters</p>
            </div>
            <div class="col-md-4">
                <div class="d-inline-block p-4 bg-white rounded-circle shadow mb-3">
                    <i class="fas fa-clock fa-3x text-primary"></i>
                </div>
                <h2 class="h4 fw-bold">{{ $stats['today_shows'] }}</h2>
                <p class="text-muted">Shows Today</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Movies Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Now Showing</h2>
            <a href="{{ route('public.movies') }}" class="btn btn-outline-dark">View All</a>
        </div>
        <div class="row">
            @forelse($featuredMovies as $movie)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card movie-card shadow-sm h-100">
                    <img src="{{ $movie->poster_url ?: '/img/movie-placeholder.jpg' }}" class="card-img-top movie-poster" alt="{{ $movie->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $movie->title }}</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-primary">{{ $movie->genre }}</span>
                            <span class="text-muted">{{ $movie->duration }} min</span>
                        </div>
                        <p class="card-text small">{{ Str::limit($movie->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-grid">
                            <a href="{{ route('public.movies.show', $movie) }}" class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No movies are currently showing. Please check back later.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Coming Soon Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Coming Soon</h2>
            <a href="{{ route('public.movies') }}?status=coming_soon" class="btn btn-outline-dark">View All</a>
        </div>
        <div class="row">
            @forelse($comingSoonMovies as $movie)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card movie-card shadow-sm h-100">
                    <div class="position-relative">
                        <img src="{{ $movie->poster_url ?: '/img/movie-placeholder.jpg' }}" class="card-img-top movie-poster" alt="{{ $movie->title }}">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-danger">Coming Soon</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $movie->title }}</h5>
                        <p class="card-text small">
                            <i class="fas fa-calendar me-1"></i> Release: {{ $movie->release_date->format('M d, Y') }}
                        </p>
                        <p class="card-text small">{{ Str::limit($movie->description, 80) }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No upcoming movies at the moment. Please check back later.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Today's Showtimes Section -->
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Today's Showtimes</h2>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Movie</th>
                        <th>Theater</th>
                        <th>Time</th>
                        <th>Available Seats</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todayShowtimes as $showtime)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $showtime->movie->poster_url ?: '/img/movie-placeholder.jpg' }}" alt="{{ $showtime->movie->title }}" class="me-2" style="width: 40px; height: 60px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold">{{ $showtime->movie->title }}</div>
                                    <div class="small text-muted">{{ $showtime->movie->genre }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $showtime->theater->name }}</td>
                        <td>{{ $showtime->show_time->format('h:i A') }}</td>
                        <td>
                            @if($showtime->available_seats > 10)
                                <span class="badge bg-success">{{ $showtime->available_seats }} seats</span>
                            @elseif($showtime->available_seats > 0)
                                <span class="badge bg-warning">{{ $showtime->available_seats }} seats</span>
                            @else
                                <span class="badge bg-danger">Sold Out</span>
                            @endif
                        </td>
                        <td>
                            @if($showtime->available_seats > 0)
                                <a href="{{ route('public.booking.form', $showtime) }}" class="btn btn-sm btn-primary">Book</a>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled>Sold Out</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No showtimes available for today.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('public.showtimes') }}" class="btn btn-outline-dark">View All Showtimes</a>
        </div>
    </div>
</section>

<!-- Promotion Section -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="fw-bold">Get Special Offers and Discounts</h2>
                <p class="lead">Join our membership program and enjoy exclusive benefits, discounts, and special screenings.</p>
                <a href="#" class="btn btn-primary btn-lg">Join Now</a>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-6 mb-4">
                        <div class="card bg-dark text-white border border-light">
                            <div class="card-body text-center">
                                <i class="fas fa-ticket-alt fa-3x mb-3"></i>
                                <h5>10% Off</h5>
                                <p class="small">On all tickets</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="card bg-dark text-white border border-light">
                            <div class="card-body text-center">
                                <i class="fas fa-popcorn fa-3x mb-3"></i>
                                <h5>Free Popcorn</h5>
                                <p class="small">With every booking</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-dark text-white border border-light">
                            <div class="card-body text-center">
                                <i class="fas fa-glass-cheers fa-3x mb-3"></i>
                                <h5>Special Events</h5>
                                <p class="small">Exclusive screenings</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-dark text-white border border-light">
                            <div class="card-body text-center">
                                <i class="fas fa-birthday-cake fa-3x mb-3"></i>
                                <h5>Birthday Offer</h5>
                                <p class="small">Free ticket on birthday</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Add any custom scripts for the home page here
    document.addEventListener('DOMContentLoaded', function() {
        // Example: Initialize a carousel or other components
    });
</script>
@endsection