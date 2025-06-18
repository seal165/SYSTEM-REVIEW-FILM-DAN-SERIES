@extends('admin.layout')

@section('title', 'Theater List')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Theaters</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Theaters</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-film me-1"></i> Theater List</span>
            <a href="{{ route('theaters.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New
            </a>
        </div>
        <div class="card-body">
            @if($theaters->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Capacity</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($theaters as $index => $theater)
                                <tr>
                                    <td>{{ $theaters->firstItem() + $index }}</td>
                                    <td>{{ $theater->name }}</td>
                                    <td>{{ $theater->capacity }}</td>
                                    <td>{{ ucfirst($theater->type) }}</td>
                                    <td>
                                        @if($theater->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $theater->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('theaters.edit', $theater->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('theaters.destroy', $theater->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this theater?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $theaters->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    No theaters found.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
