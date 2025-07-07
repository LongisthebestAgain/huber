@extends('layouts.admin')
@section('title', 'Driver Documents')
@section('subtitle', 'View and manage driver documents')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Driver Documents</h2>
        <a href="{{ route('admin.driver_documents.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-upload me-1"></i> Add Document
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white rounded shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Driver Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Documents Status</th>
                    <th>Verification Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($drivers as $driver)
                    <tr>
                        <td>{{ $driver->id }}</td>
                        <td>{{ $driver->name }}</td>
                        <td>{{ $driver->email }}</td>
                        <td>{{ $driver->phone ?? '-' }}</td>
                        <td>
                            @if($driver->driverDocuments)
                                <span class="badge bg-success">Documents Uploaded</span>
                            @else
                                <span class="badge bg-warning">No Documents</span>
                            @endif
                        </td>
                        <td>
                            @if($driver->is_verified)
                                <span class="badge bg-success">Verified</span>
                            @else
                                <span class="badge bg-warning">Pending Verification</span>
                            @endif
                        </td>
                        <td>
                            @if($driver->driverDocuments)
                                <a href="{{ route('admin.driver_documents.show', $driver->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i> View Documents
                                </a>
                            @else
                                <span class="text-muted">No documents available</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $drivers->links() }}
    </div>
@endsection 