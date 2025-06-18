@extends('admin.layout')

@section('title', 'Add New Movies')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Movies</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('movies.index') }}">Movies</a></li>
        <li class="breadcrumb-item active">Add Movies</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-clock me-1"></i>
            Movies Information
        </div>
        <div class="card-body">
            <form action="{{ route('movies.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="movie_id" class="form-label">Movie</label>
                            <select class="form-select @error('movie_id') is-invalid @enderror" id="movie_id" name="movie_id" required>
                                <option value="">Select Movie</option>
                                @foreach($movies as $movie)
                                    <option value="{{ $movie->id }}" 
                                            data-duration="{{ $movie->duration }}"
                                            {{ old('movie_id') == $movie->id ? 'selected' : '' }}>
                                        {{ $movie->title }} ({{ $movie->duration_in_hours }})
                                    </option>
                                @endforeach
                            </select>
                            @error('movie_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="theater_id" class="form-label">Theater</label>
                            <select class="form-select @error('theater_id') is-invalid @enderror" id="theater_id" name="theater_id" required>
                                <option value="">Select Theater</option>
                                @foreach($theaters as $theater)
                                    <option value="{{ $theater->id }}" 
                                            data-capacity="{{ $theater->capacity }}"
                                            {{ old('theater_id') == $theater->id ? 'selected' : '' }}>
                                        {{ $theater->name }} ({{ ucfirst($theater->type) }} - {{ $theater->capacity }} seats)
                                    </option>
                                @endforeach
                            </select>
                            @error('theater_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="show_date" class="form-label">Show Date</label>
                            <input type="date" class="form-control @error('show_date') is-invalid @enderror" 
                                   id="show_date" name="show_date" value="{{ old('show_date') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('show_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="show_time" class="form-label">Show Time</label>
                            <input type="time" class="form-control @error('show_time') is-invalid @enderror" 
                                   id="show_time" name="show_time" value="{{ old('show_time') }}" required>
                            @error('show_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="ticket_price" class="form-label">Ticket Price ($)</label>
                            <input type="number" class="form-control @error('ticket_price') is-invalid @enderror" 
                                   id="ticket_price" name="ticket_price" value="{{ old('ticket_price') }}" 
                                   step="0.01" min="0" required>
                            @error('ticket_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Schedule Preview</h6>
                                <div id="schedule-preview">
                                    <p class="text-muted">Select movie, theater, date and time to see schedule preview</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Showtime
                    </button>
                    <a href="{{ route('showtimes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Showtimes
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateSchedulePreview() {
    const movieSelect = document.getElementById('movie_id');
    const theaterSelect = document.getElementById('theater_id');
    const dateInput = document.getElementById('show_date');
    const timeInput = document.getElementById('show_time');
    const preview = document.getElementById('schedule-preview');
    
    if (movieSelect.value && theaterSelect.value && dateInput.value && timeInput.value) {
        const movieOption = movieSelect.options[movieSelect.selectedIndex];
        const theaterOption = theaterSelect.options[theaterSelect.selectedIndex];
        const duration = movieOption.dataset.duration;
        
        const startTime = timeInput.value;
        const endTime = new Date(`2000-01-01 ${startTime}`);
        endTime.setMinutes(endTime.getMinutes() + parseInt(duration));
        
        preview.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <strong>Movie:</strong> ${movieOption.text}<br>
                    <strong>Theater:</strong> ${theaterOption.text}<br>
                    <strong>Date:</strong> ${new Date(dateInput.value).toLocaleDateString()}
                </div>
                <div class="col-md-6">
                    <strong>Start Time:</strong> ${startTime}<br>
                    <strong>End Time:</strong> ${endTime.toTimeString().substr(0,5)}<br>
                    <strong>Duration:</strong> ${duration} minutes
                </div>
            </div>
        `;
    } else {
        preview.innerHTML = '<p class="text-muted">Select movie, theater, date and time to see schedule preview</p>';
    }
}

document.getElementById('movie_id').addEventListener('change', updateSchedulePreview);
document.getElementById('theater_id').addEventListener('change', updateSchedulePreview);
document.getElementById('show_date').addEventListener('change', updateSchedulePreview);
document.getElementById('show_time').addEventListener('change', updateSchedulePreview);
</script>
@endsection