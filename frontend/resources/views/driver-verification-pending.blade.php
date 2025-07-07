@extends('layouts.app')
@section('title', 'Verification Pending')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark text-center py-4">
                    <i class="fas fa-clock fa-3x mb-3"></i>
                    <h2 class="mb-0">Verification Pending</h2>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-file-alt text-primary" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h3 class="text-primary mb-4">Thank you for submitting your documents!</h3>
                    
                    <div class="alert alert-info">
                        <p class="mb-3">
                            <strong>Your driver application is currently under review.</strong>
                        </p>
                        <p class="mb-0">
                            Our team at Huber is carefully reviewing your submitted documents to ensure everything meets our safety and compliance standards.
                        </p>
                    </div>
                    
                    <div class="row mt-5">
                        <div class="col-md-4 mb-3">
                            <div class="text-center">
                                <i class="fas fa-search text-info fa-2x mb-2"></i>
                                <h5>Document Review</h5>
                                <p class="text-muted small">We're reviewing your driver's license, vehicle registration, and insurance documents.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center">
                                <i class="fas fa-shield-alt text-success fa-2x mb-2"></i>
                                <h5>Safety Check</h5>
                                <p class="text-muted small">Ensuring your vehicle meets our safety requirements and standards.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center">
                                <i class="fas fa-check-circle text-primary fa-2x mb-2"></i>
                                <h5>Final Approval</h5>
                                <p class="text-muted small">Once approved, you'll be able to start accepting rides immediately.</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="alert alert-light">
                        <h6 class="text-dark mb-3">
                            <i class="fas fa-info-circle me-2"></i>What happens next?
                        </h6>
                        <ul class="text-start text-muted">
                            <li>You'll receive an email notification once your verification is complete</li>
                            <li>This process typically takes 24-48 hours</li>
                            <li>You can log in anytime to check your status</li>
                            <li>If there are any issues, we'll contact you directly</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('logout') }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                        <button onclick="location.reload()" class="btn btn-primary">
                            <i class="fas fa-refresh me-1"></i>Check Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}
.card-header {
    border-radius: 15px 15px 0 0 !important;
}
.alert {
    border-radius: 10px;
}
</style>
@endsection 