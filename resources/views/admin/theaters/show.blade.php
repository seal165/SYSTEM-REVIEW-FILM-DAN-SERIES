@extends('admin.layout')

@section('title', 'Theater Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Theater Detail</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('theaters.index') }}">Theaters</a></li>
        <li class="breadcrumb-item active">{{ $theater->name }}</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-building me-1"></i> Theater Information
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $theater->name }}</p>
            <p><strong>Type:</strong> {{ ucfirst($theater->type) }}</p>
            <p><strong>Capacity:</strong> {{ $theater->capacity }} seats</p>
            <p><strong>Facilities:</strong> {{ $theater->facilities ?? '-' }}</p>
            <p><strong>Status:</strong> 
                @if($theater->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-clock me-1"></i> Showtimes in This Theater
        </div>
        <div class="card-body">
            @if($theater->showtimes->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Movie</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($theater->showtimes as $index => $showtime)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $showtime->movie->title ?? '-' }}</td>
                                    <td>{{ $showtime->show_date }}</td>
                                    <td>{{ \Carbon\Carbon::parse($showtime->show_time)->format('H:i') }}</td>
                                    <td>${{ number_format($showtime->ticket_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No showtimes found for this theater.</p>
            @endif
        </div>
    </div>
</div>
@endsection
