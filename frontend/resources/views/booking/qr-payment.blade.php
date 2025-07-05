@extends('layouts.app')

@section('title', 'KHQR Payment - Book Your Ride')

@section('content')
<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="text-center mb-4">
                <h1 class="fw-bold">KHQR Payment</h1>
                <p class="text-muted">Scan the QR code below to complete your payment</p>
            </div>

            <div class="row">
                <!-- Payment Summary -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow border-0" style="border-radius: 18px;">
                        <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Payment Summary</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Route</label>
                                <div class="d-flex align-items-center">
                                    <div class="text-primary fw-semibold">
                                        {{ $tripType === 'return' ? $ride->destination : $ride->station_location }}
                                    </div>
                                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                    <div class="text-primary fw-semibold">
                                        {{ $tripType === 'return' ? $ride->station_location : $ride->destination }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Date & Time</label>
                                <div class="fw-semibold">
                                    {{ $date->format('l, F d, Y') }} at {{ $time->format('H:i') }}
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Selected Seats</label>
                                <div class="fw-semibold text-success">{{ $bookingData['number_of_seats'] }} seat(s)</div>
                                <small class="text-muted">Seats: {{ implode(', ', $bookingData['selected_seats']) }}</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Passengers</label>
                                @foreach($bookingData['passenger_names'] as $index => $name)
                                    <div class="small text-muted">{{ $index + 1 }}. {{ $name }}</div>
                                @endforeach
                            </div>
                            
                            <div class="alert alert-success">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Total Amount:</strong> ${{ number_format($totalPrice, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code Section -->
                <div class="col-md-8 mb-4">
                    <div class="card shadow border-0" style="border-radius: 18px;">
                        <div class="card-header bg-success text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                            <h5 class="mb-0"><i class="fas fa-qrcode me-2"></i>KHQR Payment</h5>
                        </div>
                        <div class="card-body p-4 text-center">
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Instructions:</strong> Open your banking app and scan the QR code below to complete the payment.
                            </div>

                            <!-- QR Code Display -->
                            <div class="qr-code-container mb-4">
                                <div class="qr-code-wrapper">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=KHQR_PAYMENT_{{ $ride->id }}_{{ $tripType }}_{{ $totalPrice }}" 
                                         alt="KHQR Payment Code" 
                                         class="qr-code-image"
                                         id="qr-code">
                                </div>
                            </div>

                            <!-- Payment Status -->
                            <div class="payment-status mb-4">
                                <div class="alert alert-warning" id="payment-status">
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>Waiting for payment...</strong>
                                    <br>
                                    <small>Please scan the QR code and complete the payment in your banking app.</small>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-center gap-3">
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="refreshQR()">
                                    <i class="fas fa-sync-alt me-2"></i>Refresh QR
                                </button>
                                <button type="button" class="btn btn-success btn-lg" onclick="markAsPaid()">
                                    <i class="fas fa-check me-2"></i>I've Completed Payment
                                </button>
                            </div>

                            <!-- Back Button -->
                            <div class="mt-4">
                                <a href="{{ route('payment.show', ['rideId' => $ride->id, 'tripType' => $tripType]) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Payment Methods
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Confirmation Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="paymentModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Payment Confirmation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you have completed the payment through your banking app?</p>
                <p class="text-muted small">This will confirm your booking and mark the payment as completed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="confirmPayment()">
                    <i class="fas fa-check me-2"></i>Yes, Payment Complete
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.qr-code-container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.qr-code-wrapper {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: 2px solid #e9ecef;
}

.qr-code-image {
    max-width: 300px;
    height: auto;
    border-radius: 8px;
}

.payment-status {
    max-width: 400px;
    margin: 0 auto;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
}

.modal-content {
    border-radius: 12px;
    border: none;
}

.modal-header {
    border-radius: 12px 12px 0 0;
}
</style>

<script>
function refreshQR() {
    const qrCode = document.getElementById('qr-code');
    const timestamp = new Date().getTime();
    qrCode.src = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=KHQR_PAYMENT_{{ $ride->id }}_{{ $tripType }}_{{ $totalPrice }}&t=${timestamp}`;
    
    // Show refresh feedback
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...';
    button.disabled = true;
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 1000);
}

function markAsPaid() {
    // Show confirmation modal
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}

function confirmPayment() {
    // Create a form to submit payment data
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("payment.process", ["rideId" => $ride->id, "tripType" => $tripType]) }}';
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Add payment method
    const paymentMethod = document.createElement('input');
    paymentMethod.type = 'hidden';
    paymentMethod.name = 'payment_method';
    paymentMethod.value = 'qr';
    form.appendChild(paymentMethod);
    
    // Submit the form
    document.body.appendChild(form);
    form.submit();
}

// Auto-refresh QR code every 30 seconds
setInterval(refreshQR, 30000);

// Show payment instructions
document.addEventListener('DOMContentLoaded', function() {
    // Add some visual feedback
    const qrCode = document.getElementById('qr-code');
    qrCode.addEventListener('load', function() {
        console.log('QR code loaded successfully');
    });
    
    qrCode.addEventListener('error', function() {
        console.log('QR code failed to load, refreshing...');
        refreshQR();
    });
});
</script>
@endsection 