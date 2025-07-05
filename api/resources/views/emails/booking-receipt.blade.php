@component('mail::message')
# Booking Receipt

Thank you for booking your ride with **Hubber**!

---

## Booking Information
- **Booking Reference:** {{ $booking->booking_reference }}
- **Booking Date:** {{ $booking->created_at->format('l, F d, Y') }}
- **Booking Time:** {{ $booking->created_at->format('H:i A') }}
- **Trip Type:** {{ strtoupper($booking->trip_type) }}
- **Payment Status:** {{ strtoupper($booking->payment_status) }}
- **Payment Method:** {{ strtoupper($booking->payment_method) }}

## Trip Details
@php
    $pickupLocation = $booking->trip_type === 'return' ? $booking->ride->destination : $booking->ride->station_location;
    $destinationLocation = $booking->trip_type === 'return' ? $booking->ride->station_location : $booking->ride->destination;
    $pickupMapUrl = $booking->trip_type === 'return' ? $booking->ride->return_station_location_map_url : $booking->ride->station_location_map_url;
    $destinationMapUrl = $booking->trip_type === 'return' ? $booking->ride->return_destination_map_url : $booking->ride->destination_map_url;
@endphp
- **From:** {{ $pickupLocation }}
  @if($pickupMapUrl)
    📍 [View Pickup Location on Map]({{ $pickupMapUrl }})
  @else
    📍 [View Pickup Location on Map](https://maps.google.com/?q={{ urlencode($pickupLocation) }})
  @endif
- **To:** {{ $destinationLocation }}
  @if($destinationMapUrl)
    🎯 [View Destination on Map]({{ $destinationMapUrl }})
  @else
    🎯 [View Destination on Map](https://maps.google.com/?q={{ urlencode($destinationLocation) }})
  @endif
- **Travel Date:** {{ $booking->trip_type === 'return' ? $booking->ride->return_date->format('l, F d, Y') : $booking->ride->date->format('l, F d, Y') }}
- **Departure Time:** {{ $booking->trip_type === 'return' ? $booking->ride->return_time->format('H:i A') : $booking->ride->time->format('H:i A') }}

@component('mail::panel')
## 🗺️ Route Information
**Get Directions:** [View Route on Google Maps](https://maps.google.com/maps?f=d&saddr={{ urlencode($pickupLocation) }}&daddr={{ urlencode($destinationLocation) }})

This will show you the best route from your pickup location to your destination.
@endcomponent

## Driver Information
- **Driver Name:** {{ $booking->ride->user->name }}
- **Contact Phone:** {{ $booking->contact_phone }}

## Passenger Information
- **Number of Seats:** {{ $booking->number_of_seats }}
@if($booking->passenger_details && is_array($booking->passenger_details))
@foreach($booking->passenger_details as $index => $passenger)
- **Passenger {{ $index + 1 }}:** {{ $passenger['name'] ?? 'Unknown' }} @if(isset($passenger['seat_number']))(Seat: {{ $passenger['seat_number'] }})@endif
@endforeach
@endif
@if($booking->special_requests)
- **Special Requests:** {{ $booking->special_requests }}
@endif

## Payment Summary
- **Number of Seats:** {{ $booking->number_of_seats }}
- **Price per Seat:**
    @if($booking->trip_type === 'return' && $booking->ride->return_is_exclusive)
        ${{ number_format($booking->ride->return_exclusive_price, 2) }} (Total)
    @elseif($booking->trip_type === 'go' && $booking->ride->is_exclusive)
        ${{ number_format($booking->ride->go_to_exclusive_price, 2) }} (Total)
    @elseif($booking->trip_type === 'return')
        ${{ number_format($booking->ride->return_price_per_person, 2) }}/person
    @else
        ${{ number_format($booking->ride->go_to_price_per_person, 2) }}/person
    @endif
- **Total Amount:** ${{ number_format($booking->total_price, 2) }}

---

@component('mail::panel')
**Booking Verification QR Code**

<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $booking->booking_reference }}" alt="Booking QR Code" style="max-width: 150px; height: auto;">
@endcomponent

---

## 📱 Quick Actions
- **View Booking Details:** [My Bookings]({{ route('user.bookings') }})
- **Get Directions:** [Google Maps Route](https://maps.google.com/maps?f=d&saddr={{ urlencode($pickupLocation) }}&daddr={{ urlencode($destinationLocation) }})
- **Contact Support:** [Customer Service](mailto:support@hubber.com)

Thank you for choosing Hubber! This receipt serves as proof of your booking. Please present this email or the QR code to your driver.

If you have any questions or need support, please contact our customer service.

_Receipt generated on {{ now()->format('l, F d, Y \a\t H:i A') }}_

@endcomponent
