@extends('admin.layout')

@section('title', 'Movies Management')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mt-4">Movies Management</h1>
        <a href="{{ route('movies.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Movie
        </a>
    </div>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Movies</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @forelse($movies as $movie)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    @if($movie->poster_url)
                        <img src="{{ $movie->poster_url }}" class="card-img-top" alt="{{ $movie->title }}" style="height: 300px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="fas fa-film fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $movie->title }}</h5>
                        <p class="card-text text-muted small">
                            <i class="fas fa-tag"></i> {{ $movie->genre }} | 
                            <i class="fas fa-clock"></i> {{ $movie->duration_in_hours }} |
                            <i class="fas fa-star"></i> {{ $movie->rating }}
                        </p>
                        <p class="card-text">{{ Str::limit($movie->description, 100) }}</p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="h5 text-primary">${{ number_format($movie->price, 2) }}</span>
                                <span class="badge bg-{{ $movie->status == 'now_showing' ? 'success' : ($movie->status == 'coming_soon' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $movie->status)) }}
                                </span>
                            </div>
                            
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('movies.show', $movie) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('movies.edit', $movie) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('movies.destroy', $movie) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Are you sure you want to delete this movie?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-film fa-3x text-muted mb-3"></i>
                        <h4>No Movies Found</h4>
                        <p class="text-muted">Start by adding your first movie to the system.</p>
                        <a href="{{ route('movies.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add First Movie
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center">
        {{ $movies->links() }}
    </div>
</div>
@endsection