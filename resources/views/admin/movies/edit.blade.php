@extends('admin.layout')

@section('title', 'Edit Movie')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Movie</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('movies.index') }}">Movies</a></li>
        <li class="breadcrumb-item active">Edit Movie</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Movie Information
        </div>
        <div class="card-body">
            <form action="{{ route('movies.update', $movie->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Movie Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $movie->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="genre" class="form-label">Genre</label>
                                    <input type="text" class="form-control @error('genre') is-invalid @enderror" 
                                           id="genre" name="genre" value="{{ old('genre', $movie->genre) }}" 
                                           placeholder="e.g., Action, Comedy, Drama" required>
                                    @error('genre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duration (minutes)</label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                           id="duration" name="duration" value="{{ old('duration', $movie->duration) }}" 
                                           min="1" max="500" required>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="release_date" class="form-label">Release Date</label>
                                    <input type="date" class="form-control @error('release_date') is-invalid @enderror" 
                                           id="release_date" name="release_date" value="{{ old('release_date', $movie->release_date) }}" required>
                                    @error('release_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Rating</label>
                                    <select class="form-select @error('rating') is-invalid @enderror" id="rating" name="rating" required>
                                        <option value="">Select Rating</option>
                                        <option value="G" {{ old('rating', $movie->rating) == 'G' ? 'selected' : '' }}>G - General Audiences</option>
                                        <option value="PG" {{ old('rating', $movie->rating) == 'PG' ? 'selected' : '' }}>PG - Parental Guidance</option>
                                        <option value="PG-13" {{ old('rating', $movie->rating) == 'PG-13' ? 'selected' : '' }}>PG-13 - Parents Strongly Cautioned</option>
                                        <option value="R" {{ old('rating', $movie->rating) == 'R' ? 'selected' : '' }}>R - Restricted</option>
                                        <option value="NC-17" {{ old('rating', $movie->rating) == 'NC-17' ? 'selected' : '' }}>NC-17 - Adults Only</option>
                                    </select>
                                    @error('rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="now_showing" {{ old('status', $movie->status) == 'now_showing' ? 'selected' : '' }}>Now Showing</option>
                                        <option value="coming_soon" {{ old('status', $movie->status) == 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                                        <option value="ended" {{ old('status', $movie->status) == 'ended' ? 'selected' : '' }}>Ended</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', $movie->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="poster_url" class="form-label">Poster URL</label>
                            <input type="url" class="form-control @error('poster_url') is-invalid @enderror" 
                                   id="poster_url" name="poster_url" value="{{ old('poster_url', $movie->poster_url) }}" 
                                   placeholder="https://example.com/poster.jpg">
                            @error('poster_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="price" class="form-label">Ticket Price ($)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', $movie->price) }}" 
                                   step="0.01" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Preview</h6>
                                <div id="poster-preview" class="text-center">
                                    @if($movie->poster_url)
                                        <img src="{{ $movie->poster_url }}" class="img-fluid" style="max-height: 200px;" onerror="this.style.display='none'">
                                    @else
                                        <i class="fas fa-film fa-3x text-muted"></i>
                                        <p class="small text-muted mt-2">Poster preview will appear here</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Movie
                    </button>
                    <a href="{{ route('movies.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Movies
                    </a>
                    <a href="{{ route('movies.show', $movie->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> View Movie
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('poster_url').addEventListener('input', function() {
    const url = this.value;
    const preview = document.getElementById('poster-preview');
    
    if (url) {
        preview.innerHTML = `<img src="${url}" class="img-fluid" style="max-height: 200px;" onerror="this.style.display='none'">`;
    } else {
        preview.innerHTML = `
            <i class="fas fa-film fa-3x text-muted"></i>
            <p class="small text-muted mt-2">Poster preview will appear here</p>
        `;
    }
});

// Initialize preview on page load
document.addEventListener('DOMContentLoaded', function() {
    const posterUrl = document.getElementById('poster_url').value;
    if (posterUrl) {
        document.getElementById('poster_url').dispatchEvent(new Event('input'));
    }
});
</script>
@endsection