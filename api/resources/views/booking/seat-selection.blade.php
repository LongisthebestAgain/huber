@extends('layouts.app')

@section('title', 'Select Your Seats - Book Your Ride')

@section('content')
<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="text-center mb-4">
                <h1 class="fw-bold">Select Your Seats</h1>
                <p class="text-muted">Choose your preferred seats for this shared ride</p>
            </div>

            <form method="POST" action="{{ route('booking.process-seat-selection', ['rideId' => $ride->id, 'tripType' => $tripType]) }}">
                @csrf
                
                <!-- Hidden field to ensure number_of_seats is captured -->
                <input type="hidden" id="number_of_seats_hidden" name="number_of_seats" value="">
                
                <div class="row">
                    <!-- Ride Summary -->
                    <div class="col-md-4 mb-4">
                        <div class="card shadow border-0" style="border-radius: 18px;">
                            <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Ride Summary</h5>
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
                                    <label class="form-label fw-bold text-muted">Price per Seat</label>
                                    <div class="fw-semibold text-success fs-5">${{ number_format($pricePerSeat, 2) }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Available Seats</label>
                                    <div class="fw-semibold text-primary">{{ $availableSeats }}</div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Shared Ride:</strong> You can select specific seats for each passenger.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seat Selection -->
                    <div class="col-md-8 mb-4">
                        <div class="card shadow border-0" style="border-radius: 18px;">
                            <div class="card-header bg-success text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                                <h5 class="mb-0"><i class="fas fa-chair me-2"></i>Seat Selection</h5>
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

                                <!-- Number of Seats -->
                                <div class="mb-4" style="display: none;">
                                    <label for="number_of_seats" class="form-label fw-bold">Number of Seats</label>
                                    <select class="form-select form-select-lg" id="number_of_seats" name="number_of_seats" required readonly>
                                        <option value="">Select seats below to auto-update</option>
                                        @for($i = 1; $i <= min($availableSeats, 6); $i++)
                                            <option value="{{ $i }}">{{ $i }} seat{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                    <small class="text-muted">This will automatically update when you select seats below</small>
                                </div>

                                <!-- Seat Map -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Select Your Seats</label>
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Instructions:</strong> Simply click on the seat numbers below to select your preferred seats. The system will automatically count your selections.
                                    </div>
                                    <div class="seat-map-container">
                                        <div class="seat-map">
                                            @for($seat = 1; $seat <= $availableSeats; $seat++)
                                                <div class="seat-item">
                                                    <input type="checkbox" 
                                                           id="seat_{{ $seat }}" 
                                                           name="selected_seats[]" 
                                                           value="{{ $seat }}" 
                                                           class="seat-checkbox"
                                                           {{ in_array($seat, $bookedSeats) ? 'disabled' : '' }}>
                                                    <label for="seat_{{ $seat }}" 
                                                           class="seat-label {{ in_array($seat, $bookedSeats) ? 'booked' : '' }}">
                                                        {{ $seat }}
                                                    </label>
                                                </div>
                                            @endfor
                                        </div>
                                        <div class="seat-legend mt-3">
                                            <div class="d-flex justify-content-center gap-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="seat-legend-item available me-2"></div>
                                                    <span>Available</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="seat-legend-item selected me-2"></div>
                                                    <span>Selected</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="seat-legend-item booked me-2"></div>
                                                    <span class="text-danger fw-bold">Booked</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-3">
                                            <div id="seat-selection-status" class="alert alert-secondary" style="display: none;">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <span id="status-text">Select your seats</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Passenger Details -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Passenger Names</label>
                                    <div id="passenger-names-container">
                                        <div class="passenger-name-group mb-2">
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="passenger_names[]" 
                                                   placeholder="Passenger 1 name" 
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="mb-4">
                                    <label for="contact_phone" class="form-label fw-bold">Contact Phone</label>
                                    <input type="tel" 
                                           class="form-control form-control-lg" 
                                           id="contact_phone" 
                                           name="contact_phone" 
                                           placeholder="Enter your phone number" 
                                           required>
                                </div>

                                <!-- Special Requests -->
                                <div class="mb-4">
                                    <label for="special_requests" class="form-label fw-bold">Special Requests (Optional)</label>
                                    <textarea class="form-control" 
                                              id="special_requests" 
                                              name="special_requests" 
                                              rows="3" 
                                              placeholder="Any special requests or notes..."></textarea>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('find.rides') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Rides
                                    </a>
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-check me-2"></i>Confirm Booking
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.seat-map-container {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 2rem;
}

.seat-map {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
    gap: 1rem;
    max-width: 600px;
    margin: 0 auto;
}

.seat-item {
    text-align: center;
}

.seat-checkbox {
    display: none;
}

.seat-label {
    display: inline-block;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    background: #28a745;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.seat-label:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.seat-checkbox:checked + .seat-label {
    background: #007bff;
    border-color: #0056b3;
    transform: scale(1.1);
}

.seat-label.booked {
    background: #dc3545 !important;
    cursor: not-allowed;
    opacity: 0.8;
    border-color: #dc3545 !important;
    transform: none !important;
}

.seat-legend-item {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    border: 2px solid #dee2e6;
}

.seat-legend-item.available {
    background: #28a745;
}

.seat-legend-item.selected {
    background: #007bff;
}

.seat-legend-item.booked {
    background: #dc3545;
}

.passenger-name-group {
    transition: all 0.3s ease;
}

.passenger-name-group.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const numberOfSeatsSelect = document.getElementById('number_of_seats');
    const passengerNamesContainer = document.getElementById('passenger-names-container');
    const seatCheckboxes = document.querySelectorAll('.seat-checkbox:not([disabled])');
    const seatSelectionStatus = document.getElementById('seat-selection-status');
    const statusText = document.getElementById('status-text');
    const maxSeats = {{ $availableSeats }};

    // Handle seat selection
    seatCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const selectedSeats = document.querySelectorAll('.seat-checkbox:checked:not([disabled])').length;
            
            // Auto-update the number of seats dropdown
            numberOfSeatsSelect.value = selectedSeats;
            
            // Also update the hidden field
            document.getElementById('number_of_seats_hidden').value = selectedSeats;
            
            // Update passenger names based on selected seats
            updatePassengerNames(selectedSeats);
            updateStatus();
        });
    });

    // Manual dropdown change (optional, for users who prefer to use dropdown)
    numberOfSeatsSelect.addEventListener('change', function() {
        const selectedSeats = parseInt(this.value);
        const currentSelectedSeats = document.querySelectorAll('.seat-checkbox:checked:not([disabled])').length;
        
        if (selectedSeats < currentSelectedSeats) {
            // If user reduces number of seats, uncheck excess seats
            const checkedSeats = document.querySelectorAll('.seat-checkbox:checked:not([disabled])');
            for (let i = selectedSeats; i < checkedSeats.length; i++) {
                checkedSeats[i].checked = false;
            }
        }
        
        updatePassengerNames(selectedSeats);
        updateStatus();
    });

    function updatePassengerNames(count) {
        passengerNamesContainer.innerHTML = '';
        
        for (let i = 1; i <= count; i++) {
            const group = document.createElement('div');
            group.className = 'passenger-name-group fade-in';
            group.innerHTML = `
                <input type="text" 
                       class="form-control" 
                       name="passenger_names[]" 
                       placeholder="Passenger ${i} name" 
                       required>
            `;
            passengerNamesContainer.appendChild(group);
        }
    }

    function updateStatus() {
        const numberOfSeats = parseInt(numberOfSeatsSelect.value);
        const selectedSeats = document.querySelectorAll('.seat-checkbox:checked:not([disabled])').length;
        
        if (!numberOfSeats || numberOfSeats === 0) {
            seatSelectionStatus.style.display = 'none';
            return;
        }
        
        seatSelectionStatus.style.display = 'block';
        
        if (selectedSeats === 0) {
            statusText.textContent = `Click on seat numbers to select your seats`;
            seatSelectionStatus.className = 'alert alert-info';
        } else if (selectedSeats === 1) {
            statusText.textContent = `Selected ${selectedSeats} seat - Ready to book!`;
            seatSelectionStatus.className = 'alert alert-success';
        } else {
            statusText.textContent = `Selected ${selectedSeats} seats - Ready to book!`;
            seatSelectionStatus.className = 'alert alert-success';
        }
    }

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const numberOfSeats = parseInt(numberOfSeatsSelect.value);
        const selectedSeats = document.querySelectorAll('.seat-checkbox:checked:not([disabled])').length;
        const passengerNames = document.querySelectorAll('input[name="passenger_names[]"]');
        
        if (!numberOfSeats || numberOfSeats === 0) {
            e.preventDefault();
            alert('Please select at least one seat.');
            return;
        }
        
        if (selectedSeats !== numberOfSeats) {
            e.preventDefault();
            alert(`Please select exactly ${numberOfSeats} seat(s). You have selected ${selectedSeats} seat(s).`);
            return;
        }
        
        let validNames = 0;
        passengerNames.forEach(input => {
            if (input.value.trim() !== '') {
                validNames++;
            }
        });
        
        if (validNames !== numberOfSeats) {
            e.preventDefault();
            alert(`Please provide names for all ${numberOfSeats} passenger(s). You have provided ${validNames} name(s).`);
            return;
        }
        
        // Ensure the number_of_seats field is set correctly
        numberOfSeatsSelect.value = selectedSeats;
        document.getElementById('number_of_seats_hidden').value = selectedSeats;
    });
});
</script>
@endsection 