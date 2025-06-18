@extends('admin.layout')

@section('title', 'Showtime Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Showtime Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('showtimes.index') }}">Showtimes</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-clock me-1"></i>
            Showtime Information
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Movie</label>
                    <p class="form-control-plaintext">{{ $showtime->movie->title }} ({{ $showtime->movie->duration }} mins)</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Theater</label>
                    <p class="form-control-plaintext">
                        {{ $showtime->theater->name }} ({{ ucfirst($showtime->theater->type) }} - {{ $showtime->theater->capacity }} seats)
                    </p>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Show Date</label>
                    <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($showtime->show_date)->format('F j, Y') }}</p>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Show Time</label>
                    <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($showtime->show_time)->format('H:i') }}</p>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ticket Price</label>
                    <p class="form-control-plaintext">${{ number_format($showtime->ticket_price, 2) }}</p>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Schedule Summary</h6>
                            @php
                                $start = \Carbon\Carbon::parse($showtime->show_date . ' ' . $showtime->show_time);
                                $end = (clone $start)->addMinutes($showtime->movie->duration ?? 0);
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Movie:</strong> {{ $showtime->movie->title }}<br>
                                    <strong>Theater:</strong> {{ $showtime->theater->name }}<br>
                                    <strong>Date:</strong> {{ $start->format('l, F j, Y') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Start Time:</strong> {{ $start->format('H:i') }}<br>
                                    <strong>End Time:</strong> {{ $end->format('H:i') }}<br>
                                    <strong>Duration:</strong> {{ $showtime->movie->duration }} minutes
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('showtimes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Showtimes
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
