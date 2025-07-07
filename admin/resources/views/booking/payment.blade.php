@extends('layouts.app')

@section('title', 'Complete Payment - Book Your Ride')

@section('content')
@php
    $isExclusive = ($tripType === 'return' && $ride->is_two_way) ? $ride->return_is_exclusive : $ride->is_exclusive;
    $totalPrice = $isExclusive ? $pricePerSeat : ($pricePerSeat * ($bookingData['number_of_seats'] ?? 1));
@endphp
<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="text-center mb-4">
                <h1 class="fw-bold">Complete Your Payment</h1>
                <p class="text-muted">Choose your preferred payment method to confirm your booking</p>
            </div>

            <div class="row">
                <!-- Booking Summary -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow border-0" style="border-radius: 18px;">
                        <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Booking Summary</h5>
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
                                @if($isExclusive)
                                    <div class="fw-semibold text-success">Exclusive Ride</div>
                                    <small class="text-muted">Entire vehicle reserved</small>
                                @else
                                    <div class="fw-semibold text-success">{{ $bookingData['number_of_seats'] }} seat(s)</div>
                                    <small class="text-muted">Seats: {{ implode(', ', $bookingData['selected_seats']) }}</small>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Passengers</label>
                                @if(isset($bookingData) && isset($bookingData['passenger_names']))
                                    @foreach($bookingData['passenger_names'] as $index => $name)
                                        <div class="small text-muted">{{ $index + 1 }}. {{ $name }}</div>
                                    @endforeach
                                @else
                                    <div class="small text-muted">1. {{ $user->name }}</div>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Price per Seat</label>
                                <div class="fw-semibold text-success fs-5">${{ number_format($pricePerSeat, 2) }}</div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Total Amount:</strong> ${{ number_format($totalPrice, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="col-md-8 mb-4">
                    <div class="card shadow border-0" style="border-radius: 18px;">
                        <div class="card-header bg-success text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                            <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Method</h5>
                        </div>
                        <div class="card-body p-4">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('payment.process', ['rideId' => $ride->id, 'tripType' => $tripType]) }}" id="payment-form">
                                @csrf
                                
                                <!-- Payment Method Selection -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Choose Payment Method</label>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="payment-method-card" data-method="visa">
                                                <input type="radio" name="payment_method" value="visa" id="visa" class="payment-method-radio">
                                                <label for="visa" class="payment-method-label">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fab fa-cc-visa fs-2 text-primary me-3"></i>
                                                        <div>
                                                            <div class="fw-bold">Visa</div>
                                                            <small class="text-muted">Credit/Debit Card</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="payment-method-card" data-method="mastercard">
                                                <input type="radio" name="payment_method" value="mastercard" id="mastercard" class="payment-method-radio">
                                                <label for="mastercard" class="payment-method-label">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fab fa-cc-mastercard fs-2 text-warning me-3"></i>
                                                        <div>
                                                            <div class="fw-bold">Mastercard</div>
                                                            <small class="text-muted">Credit/Debit Card</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="payment-method-card" data-method="qr">
                                                <input type="radio" name="payment_method" value="qr" id="qr" class="payment-method-radio">
                                                <label for="qr" class="payment-method-label">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-qrcode fs-2 text-success me-3"></i>
                                                        <div>
                                                            <div class="fw-bold">KHQR</div>
                                                            <small class="text-muted">QR Code Payment</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Credit Card Form -->
                                <div id="credit-card-form" class="payment-form-section" style="display: none;">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="card_holder_name" class="form-label fw-bold">Cardholder Name</label>
                                            <input type="text" 
                                                   class="form-control form-control-lg" 
                                                   id="card_holder_name" 
                                                   name="card_holder_name" 
                                                   placeholder="Enter cardholder name"
                                                   maxlength="255">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="card_number" class="form-label fw-bold">Card Number</label>
                                            <input type="text" 
                                                   class="form-control form-control-lg" 
                                                   id="card_number" 
                                                   name="card_number" 
                                                   placeholder="1234 5678 9012 3456"
                                                   maxlength="19">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="card_expiry" class="form-label fw-bold">Expiry Date</label>
                                            <input type="text" 
                                                   class="form-control form-control-lg" 
                                                   id="card_expiry" 
                                                   name="card_expiry" 
                                                   placeholder="MM/YY"
                                                   maxlength="5">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="card_cvv" class="form-label fw-bold">CVV</label>
                                            <input type="text" 
                                                   class="form-control form-control-lg" 
                                                   id="card_cvv" 
                                                   name="card_cvv" 
                                                   placeholder="123"
                                                   maxlength="4">
                                        </div>
                                    </div>
                                </div>

                                <!-- QR Payment Section -->
                                <div id="qr-payment-section" class="payment-form-section" style="display: none;">
                                    <div class="text-center">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>QR Payment:</strong> Click the button below to proceed with KHQR payment.
                                        </div>
                                        <a href="{{ route('payment.qr', ['rideId' => $ride->id, 'tripType' => $tripType]) }}" 
                                           class="btn btn-success btn-lg">
                                            <i class="fas fa-qrcode me-2"></i>Proceed with KHQR
                                        </a>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between mt-4">
                                    @if($isExclusive)
                                        <a href="{{ route('find.rides') }}" 
                                           class="btn btn-outline-secondary btn-lg">
                                            <i class="fas fa-arrow-left me-2"></i>Back to Find Rides
                                        </a>
                                    @else
                                        <a href="{{ route('booking.seat-selection', ['rideId' => $ride->id, 'tripType' => $tripType]) }}" 
                                           class="btn btn-outline-secondary btn-lg">
                                            <i class="fas fa-arrow-left me-2"></i>Back to Seat Selection
                                        </a>
                                    @endif
                                    <button type="submit" id="pay-button" class="btn btn-success btn-lg" disabled>
                                        <i class="fas fa-lock me-2"></i>Pay ${{ number_format($totalPrice, 2) }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-method-card {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.payment-method-card:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.payment-method-card.selected {
    border-color: #28a745;
    background-color: #f8fff9;
}

.payment-method-radio {
    display: none;
}

.payment-method-label {
    cursor: pointer;
    margin: 0;
    width: 100%;
}

.payment-form-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
}

#card_number {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="%23ccc" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 20px;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('.payment-method-radio');
    const creditCardForm = document.getElementById('credit-card-form');
    const qrPaymentSection = document.getElementById('qr-payment-section');
    const payButton = document.getElementById('pay-button');
    const cardNumber = document.getElementById('card_number');
    const cardExpiry = document.getElementById('card_expiry');
    const cardCvv = document.getElementById('card_cvv');

    // Handle payment method selection
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            // Remove selected class from all cards
            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to current card
            this.closest('.payment-method-card').classList.add('selected');
            
            // Show/hide forms based on selection
            if (this.value === 'qr') {
                creditCardForm.style.display = 'none';
                qrPaymentSection.style.display = 'block';
                payButton.disabled = true;
            } else {
                creditCardForm.style.display = 'block';
                qrPaymentSection.style.display = 'none';
                payButton.disabled = false;
            }
        });
    });

    // Card number formatting
    cardNumber.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
        e.target.value = value;
    });

    // Expiry date formatting
    cardExpiry.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    // CVV formatting (numbers only)
    cardCvv.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    // Form validation
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', function(e) {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!selectedMethod) {
            e.preventDefault();
            alert('Please select a payment method.');
            return;
        }

        if (selectedMethod.value !== 'qr') {
            const cardHolderName = document.getElementById('card_holder_name').value.trim();
            const cardNumberValue = cardNumber.value.trim();
            const cardExpiryValue = cardExpiry.value.trim();
            const cardCvvValue = cardCvv.value.trim();

            if (!cardHolderName) {
                e.preventDefault();
                alert('Please enter the cardholder name.');
                return;
            }

            if (!cardNumberValue) {
                e.preventDefault();
                alert('Please enter a card number.');
                return;
            }

            if (!cardExpiryValue) {
                e.preventDefault();
                alert('Please enter an expiry date.');
                return;
            }

            if (!cardCvvValue) {
                e.preventDefault();
                alert('Please enter a CVV.');
                return;
            }
        }
    });
});
</script>
@endsection 