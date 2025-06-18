@extends('admin.layout')

@section('title', 'Edit Theater')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Theater</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('theaters.index') }}">Theaters</a></li>
        <li class="breadcrumb-item active">Edit Theater</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Theater Information
        </div>
        <div class="card-body">
            <form action="{{ route('theaters.update', $theater->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Theater Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $theater->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="capacity" class="form-label">Capacity</label>
                    <input type="number" name="capacity" id="capacity"
                           class="form-control @error('capacity') is-invalid @enderror"
                           value="{{ old('capacity', $theater->capacity) }}" min="1" max="1000" required>
                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">Select Type</option>
                        <option value="regular" {{ old('type', $theater->type) == 'regular' ? 'selected' : '' }}>Regular</option>
                        <option value="imax" {{ old('type', $theater->type) == 'imax' ? 'selected' : '' }}>IMAX</option>
                        <option value="vip" {{ old('type', $theater->type) == 'vip' ? 'selected' : '' }}>VIP</option>
                        <option value="4dx" {{ old('type', $theater->type) == '4dx' ? 'selected' : '' }}>4DX</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="facilities" class="form-label">Facilities</label>
                    <textarea name="facilities" id="facilities"
                              class="form-control @error('facilities') is-invalid @enderror"
                              rows="3">{{ old('facilities', $theater->facilities) }}</textarea>
                    @error('facilities')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', $theater->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>

                <div class="mb-3 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Theater
                    </button>
                    <a href="{{ route('theaters.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Theaters
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
