@extends('layouts.app')
@section('title', 'Edit Driver Document')
@section('content')
<div class="container mt-5">
    <h1>Edit Driver Document</h1>
    <form method="POST" action="{{ route('admin.driver_documents.update', $document->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="user_id" class="form-label">User ID</label>
            <input type="number" class="form-control" id="user_id" name="user_id" value="{{ $document->user_id }}" required>
        </div>
        <div class="mb-3">
            <label for="license_number" class="form-label">License Number</label>
            <input type="text" class="form-control" id="license_number" name="license_number" value="{{ $document->license_number }}" required>
        </div>
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.driver_documents.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 