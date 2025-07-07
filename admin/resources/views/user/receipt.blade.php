<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt - {{ $booking->booking_reference }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .receipt-container {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
            body {
                background: white !important;
            }
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .receipt-body {
            padding: 2rem;
        }
        
        .receipt-section {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .receipt-section:last-child {
            border-bottom: none;
        }
        
        .receipt-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .receipt-subtitle {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 1rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
        }
        
        .info-label {
            font-weight: 600;
            color: #555;
        }
        
        .info-value {
            color: #333;
        }
        
        .total-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 2rem;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .total-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }
        
        .passenger-list {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        
        .passenger-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .passenger-item:last-child {
            border-bottom: none;
        }
        
        .qr-code {
            text-align: center;
            margin: 2rem 0;
        }
        
        .qr-code img {
            max-width: 150px;
            height: auto;
        }
        
        .footer-note {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-light">
    <div class="print-button no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print me-2"></i>Print Receipt
        </button>
        <a href="{{ route('user.bookings') }}" class="btn btn-outline-secondary ms-2">
            <i class="fas fa-arrow-left me-2"></i>Back to Bookings
        </a>
    </div>

    <div class="container-fluid py-4">
        <div class="receipt-container">
            <!-- Receipt Header -->
            <div class="receipt-header">
                <div class="receipt-title">
                    <i class="fas fa-receipt me-2"></i>BOOKING RECEIPT
                </div>
                <div class="receipt-subtitle">
                    Hubber - Ride Booking Service
                </div>
                <div class="mt-3">
                    <span class="badge bg-light text-dark fs-6">{{ $booking->booking_reference }}</span>
                </div>
            </div>

            <!-- Receipt Body -->
            <div class="receipt-body">
                <!-- Booking Information -->
                <div class="receipt-section">
                    <div class="receipt-title">Booking Information</div>
                    <div class="info-row">
                        <span class="info-label">Booking Reference:</span>
                        <span class="info-value">{{ $booking->booking_reference }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Booking Date:</span>
                        <span class="info-value">{{ $booking->created_at->format('l, F d, Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Booking Time:</span>
                        <span class="info-value">{{ $booking->created_at->format('H:i A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Trip Type:</span>
                        <span class="info-value">
                            <span class="badge {{ $booking->trip_type === 'return' ? 'bg-warning' : 'bg-primary' }}">
                                {{ strtoupper($booking->trip_type) }} TRIP
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Status:</span>
                        <span class="info-value">
                            <span class="badge bg-success">{{ strtoupper($booking->payment_status) }}</span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Method:</span>
                        <span class="info-value">{{ strtoupper($booking->payment_method) }}</span>
                    </div>
                </div>

                <!-- Trip Details -->
                <div class="receipt-section">
                    <div class="receipt-title">Trip Details</div>
                    <div class="info-row">
                        <span class="info-label">From:</span>
                        <span class="info-value">
                            {{ $booking->trip_type === 'return' ? $booking->ride->destination : $booking->ride->station_location }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">To:</span>
                        <span class="info-value">
                            {{ $booking->trip_type === 'return' ? $booking->ride->station_location : $booking->ride->destination }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Travel Date:</span>
                        <span class="info-value">
                            {{ $booking->trip_type === 'return' ? $booking->ride->return_date->format('l, F d, Y') : $booking->ride->date->format('l, F d, Y') }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Departure Time:</span>
                        <span class="info-value">
                            {{ $booking->trip_type === 'return' ? $booking->ride->return_time->format('H:i A') : $booking->ride->time->format('H:i A') }}
                        </span>
                    </div>
                </div>

                <!-- Driver Information -->
                <div class="receipt-section">
                    <div class="receipt-title">Driver Information</div>
                    <div class="info-row">
                        <span class="info-label">Driver Name:</span>
                        <span class="info-value">{{ $booking->ride->user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Contact Phone:</span>
                        <span class="info-value">{{ $booking->contact_phone }}</span>
                    </div>
                </div>

                <!-- Passenger Information -->
                <div class="receipt-section">
                    <div class="receipt-title">Passenger Information</div>
                    <div class="info-row">
                        <span class="info-label">Number of Seats:</span>
                        <span class="info-value">{{ $booking->number_of_seats }}</span>
                    </div>
                    
                    @if($booking->passenger_details && is_array($booking->passenger_details))
                        <div class="passenger-list">
                            <div class="receipt-subtitle">Passenger Details:</div>
                            @foreach($booking->passenger_details as $index => $passenger)
                                <div class="passenger-item">
                                    <span class="info-label">Passenger {{ $index + 1 }}:</span>
                                    <span class="info-value">{{ $passenger['name'] ?? 'Unknown' }}</span>
                                </div>
                                @if(isset($passenger['seat_number']))
                                    <div class="passenger-item">
                                        <span class="info-label">Seat Number:</span>
                                        <span class="info-value">{{ $passenger['seat_number'] }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @if($booking->special_requests)
                        <div class="info-row">
                            <span class="info-label">Special Requests:</span>
                            <span class="info-value">{{ $booking->special_requests }}</span>
                        </div>
                    @endif
                </div>

                <!-- Payment Summary -->
                <div class="total-section">
                    <div class="receipt-title">Payment Summary</div>
                    <div class="total-row">
                        <span class="info-label">Number of Seats:</span>
                        <span class="info-value">{{ $booking->number_of_seats }}</span>
                    </div>
                    <div class="total-row">
                        <span class="info-label">Price per Seat:</span>
                        <span class="info-value">
                            @if($booking->trip_type === 'return' && $booking->ride->return_is_exclusive)
                                ${{ number_format($booking->ride->return_exclusive_price, 2) }} (Total)
                            @elseif($booking->trip_type === 'go' && $booking->ride->is_exclusive)
                                ${{ number_format($booking->ride->go_to_exclusive_price, 2) }} (Total)
                            @elseif($booking->trip_type === 'return')
                                ${{ number_format($booking->ride->return_price_per_person, 2) }}/person
                            @else
                                ${{ number_format($booking->ride->go_to_price_per_person, 2) }}/person
                            @endif
                        </span>
                    </div>
                    <div class="total-row">
                        <span class="info-label">Total Amount:</span>
                        <span class="total-amount">${{ number_format($booking->total_price, 2) }}</span>
                    </div>
                </div>

                <!-- QR Code for Verification -->
                <div class="qr-code">
                    <div class="receipt-subtitle">Booking Verification QR Code</div>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $booking->booking_reference }}" 
                         alt="Booking QR Code">
                </div>

                <!-- Footer -->
                <div class="footer-note">
                    <p><strong>Thank you for choosing Hubber!</strong></p>
                    <p>This receipt serves as proof of your booking. Please present this receipt or the QR code to your driver.</p>
                    <p>For any questions or support, please contact our customer service.</p>
                    <p class="mt-3">
                        <small>
                            Receipt generated on {{ now()->format('l, F d, Y \a\t H:i A') }}<br>
                            Hubber - Your trusted ride booking platform
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 