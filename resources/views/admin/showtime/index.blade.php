@extends('admin.layout')

@section('title', 'Showtimes Management')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mt-4">Showtimes Management</h1>
        <a href="{{ route('showtimes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Showtime
        </a>
    </div>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Showtimes</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-clock me-1"></i>
            Movie Showtimes
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Movie</th>
                            <th>Theater</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Available Seats</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($showtimes as $showtime)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($showtime->movie->poster_url)
                                            <img src="{{ $showtime->movie->poster_url }}" 
                                                 class="rounded me-2" 
                                                 style="width: 40px; height: 60px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ $showtime->movie->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $showtime->movie->genre }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $showtime->theater->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ ucfirst($showtime->theater->type) }} | 
                                        Capacity: {{ $showtime->theater->capacity }}
                                    </small>
                                </td>
                                <td>{{ $showtime->show_date->format('M d, Y') }}</td>
                                <td>{{ $showtime->show_time->format('H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $showtime->available_seats > 0 ? 'success' : 'danger' }}">
                                        {{ $showtime->available_seats }} / {{ $showtime->theater->capacity }}
                                    </span>
                                </td>
                                <td>${{ number_format($showtime->ticket_price, 2) }}</td>
                                <td>
                                    @php
                                        $now = now();
                                        $showtimeDateTime = $showtime->show_date->format('Y-m-d') . ' ' . $showtime->show_time->format('H:i:s');
                                        $isUpcoming = $now < $showtimeDateTime;
                                        $isOngoing = $now >= $showtimeDateTime && $now <= date('Y-m-d H:i:s', strtotime($showtimeDateTime . ' +' . $showtime->movie->duration . ' minutes'));
                                        $isEnded = $now > date('Y-m-d H:i:s', strtotime($showtimeDateTime . ' +' . $showtime->movie->duration . ' minutes'));
                                    @endphp
                                    
                                    @if($isUpcoming)
                                        <span class="badge bg-primary">Upcoming</span>
                                    @elseif($isOngoing)
                                        <span class="badge bg-success">Now Playing</span>
                                    @else
                                        <span class="badge bg-secondary">Ended</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('showtimes.show', $showtime) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('showtimes.edit', $showtime) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('showtimes.destroy', $showtime) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this showtime?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                    <h5>No Showtimes Found</h5>
                                    <p class="text-muted">Start by creating your first showtime schedule.</p>
                                    <a href="{{ route('showtimes.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add First Showtime
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $showtimes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection