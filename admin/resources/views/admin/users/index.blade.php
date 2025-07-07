@extends('layouts.admin')
@section('title', 'Manage Users')
@section('subtitle', 'View, create, edit and manage all registered users')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="fas fa-users me-2 text-primary"></i>Users</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-user-plus me-1"></i> Create New User
        </a>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2 text-secondary"></i>User List</h5>
                <span class="badge bg-primary">{{ $users->total() }} Total Users</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 px-3">ID</th>
                            <th class="border-0">Name</th>
                            <th class="border-0">Email</th>
                            <th class="border-0">Phone</th>
                            <th class="border-0">Role</th>
                            <th class="border-0">Created</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="user-row">
                                <td class="px-3">
                                    <span class="badge bg-secondary">#{{ $user->id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;">
                                        <i class="fas fa-envelope me-1 text-muted"></i>{{ $user->email }}
                                    </div>
                                </td>
                                <td>
                                    @if($user->phone)
                                        <i class="fas fa-phone me-1 text-muted"></i>{{ $user->phone }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->role === 'driver')
                                        <span class="badge bg-success"><i class="fas fa-user-tie me-1"></i>Driver</span>
                                    @elseif($user->role === 'admin')
                                        <span class="badge bg-danger"><i class="fas fa-user-shield me-1"></i>Admin</span>
                                    @else
                                        <span class="badge bg-info"><i class="fas fa-user me-1"></i>User</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>{{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm" title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete User">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-center">
        {{ $users->links() }}
    </div>
@endsection

<style>
.user-row:hover { background-color: #f8f9fa; }
.user-avatar {
    width: 32px;
    height: 32px;
    background: #e3f2fd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}
.badge { font-size: 0.75rem; }
.btn-group .btn { border-radius: 0.375rem !important; }
.btn-group .btn:not(:last-child) { margin-right: 0.25rem; }
.table th { font-weight: 600; color: #495057; }
@media (max-width: 767px) {
    .btn-group .btn { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
    .table-responsive { font-size: 0.875rem; }
}
</style> 