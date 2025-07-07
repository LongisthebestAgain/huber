@extends('layouts.admin')
@section('title', 'Add Driver Document')
@section('subtitle', 'Upload a new driver document')
@section('content')
    <div class="card p-4 shadow-sm mx-auto" style="max-width: 600px;">
        <form action="{{ route('admin.driver_documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="driver_id" class="form-label">Driver</label>
                <select class="form-select" id="driver_id" name="driver_id" required>
                    <option value="">Select Driver</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="document_type" class="form-label">Document Type</label>
                <input type="text" class="form-control" id="document_type" name="document_type" value="{{ old('document_type') }}" required>
            </div>
            <div class="mb-3">
                <label for="document_file" class="form-label">Document File</label>
                <input type="file" class="form-control" id="document_file" name="document_file" required>
            </div>
            <button type="submit" class="btn btn-success w-100">
                <i class="fas fa-upload me-1"></i> Upload Document
            </button>
        </form>
    </div>
@endsection 