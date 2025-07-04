@extends('layouts.ride-management')

@section('title', 'Edit Ride - Hubber')

@section('main')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center" style="border-radius: 12px 12px 0 0;">
        <i class="fas fa-edit me-2"></i>
        <h4 class="mb-0">Edit Ride</h4>
    </div>
    <div class="card-body bg-light" style="border-radius: 0 0 12px 12px;">
        <form method="POST" action="{{ route('driver.rides.update', $ride->id) }}">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
            <div class="mb-3">
                        <label for="station_location" class="form-label fw-semibold">Station Location</label>
                        <input type="text" class="form-control" id="station_location" name="station_location" value="{{ old('station_location', $ride->station_location) }}" required placeholder="Enter pickup station...">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="destination" class="form-label fw-semibold">Destination</label>
                        <input type="text" class="form-control" id="destination" name="destination" value="{{ old('destination', $ride->destination) }}" required placeholder="Enter destination...">
            </div>
                </div>
                <div class="col-md-3">
            <div class="mb-3">
                        <label for="date" class="form-label fw-semibold">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $ride->date) }}" required>
            </div>
                </div>
                <div class="col-md-3">
            <div class="mb-3">
                        <label for="time" class="form-label fw-semibold">Time</label>
                <input type="time" class="form-control" id="time" name="time" value="{{ old('time', $ride->time) }}" required>
            </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="available_seats" class="form-label fw-semibold">Available Seats</label>
                        <input type="number" class="form-control" id="available_seats" name="available_seats" min="1" value="{{ old('available_seats', $ride->available_seats) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
            <div class="mb-3">
                        <label for="go_to_price_per_person" class="form-label fw-semibold">Go To Price Per Person</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="go_to_price_per_person" name="go_to_price_per_person" value="{{ old('go_to_price_per_person', $ride->go_to_price_per_person) }}" placeholder="$ per person">
                    </div>
            </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
            <div class="mb-3">
                        <label class="form-label fw-semibold">Type</label>
                        <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="is_exclusive" id="exclusive" value="1" {{ old('is_exclusive', $ride->is_exclusive) == '1' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="exclusive">Exclusive</label>
                </div>
                        <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="is_exclusive" id="shared" value="0" {{ old('is_exclusive', $ride->is_exclusive) == '0' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="shared">Shared</label>
                </div>
            </div>
                </div>
                <div class="col-md-6">
            <div class="mb-3">
                        <label class="form-label fw-semibold">Trip Type</label>
                        <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="is_two_way" id="one_way" value="0" {{ old('is_two_way', $ride->is_two_way) == '0' ? 'checked' : '' }} required onclick="toggleReturnFields()">
                    <label class="form-check-label" for="one_way">One Way</label>
                </div>
                        <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="is_two_way" id="two_way" value="1" {{ old('is_two_way', $ride->is_two_way) == '1' ? 'checked' : '' }} required onclick="toggleReturnFields()">
                    <label class="form-check-label" for="two_way">Two Way (Return)</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                <div class="mb-3">
                        <label for="station_location_map_url" class="form-label">Station Location Google Maps Link (optional)</label>
                        <input type="url" class="form-control" id="station_location_map_url" name="station_location_map_url" value="{{ old('station_location_map_url', $ride->station_location_map_url) }}" placeholder="https://maps.google.com/...">
                    </div>
                </div>
                <div class="col-md-6">
                <div class="mb-3">
                        <label for="destination_map_url" class="form-label">Destination Google Maps Link (optional)</label>
                        <input type="url" class="form-control" id="destination_map_url" name="destination_map_url" value="{{ old('destination_map_url', $ride->destination_map_url) }}" placeholder="https://maps.google.com/...">
                    </div>
                </div>
            </div>
            <div id="return-fields" class="bg-white p-4 rounded shadow-sm mb-3" style="display: none;">
                <hr>
                <h5 class="mb-3 text-success"><i class="fas fa-undo-alt me-2"></i>Return Trip Details</h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="return_station_location" class="form-label fw-semibold">Return Station Location</label>
                            <input type="text" class="form-control" id="return_station_location" name="return_station_location" value="{{ old('return_station_location', $ride->return_station_location) }}" placeholder="Enter return pickup station...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="return_destination" class="form-label fw-semibold">Return Destination</label>
                            <input type="text" class="form-control bg-light" id="return_destination_display" value="{{ old('station_location', $ride->station_location) }}" readonly>
                            <input type="hidden" id="return_destination" name="return_destination" value="{{ old('station_location', $ride->station_location) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                <div class="mb-3">
                            <label for="return_date" class="form-label fw-semibold">Return Date</label>
                    <input type="date" class="form-control" id="return_date" name="return_date" value="{{ old('return_date', $ride->return_date) }}">
                </div>
                    </div>
                    <div class="col-md-3">
                <div class="mb-3">
                            <label for="return_time" class="form-label fw-semibold">Return Time</label>
                    <input type="time" class="form-control" id="return_time" name="return_time" value="{{ old('return_time', $ride->return_time) }}">
                </div>
                    </div>
                    <div class="col-md-3">
                <div class="mb-3">
                            <label for="return_available_seats" class="form-label fw-semibold">Return Available Seats</label>
                    <input type="number" class="form-control" id="return_available_seats" name="return_available_seats" value="{{ old('return_available_seats', $ride->return_available_seats) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3" id="return-price-per-person-field">
                            <label for="return_price_per_person" class="form-label fw-semibold">Return Price Per Person</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="return_price_per_person" name="return_price_per_person" value="{{ old('return_price_per_person', $ride->return_price_per_person) }}" placeholder="$ per person">
                        </div>
                    </div>
                    <div class="col-md-3" id="return-exclusive-price-field">
                        <div class="mb-3">
                            <label for="return_exclusive_price" class="form-label fw-semibold">Return Exclusive Price (Total)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="return_exclusive_price" name="return_exclusive_price" value="{{ old('return_exclusive_price', $ride->return_exclusive_price) }}" placeholder="$ total">
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                <div class="mb-3">
                            <label class="form-label fw-semibold">Return Type</label>
                            <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="return_is_exclusive" id="return_exclusive" value="1" {{ old('return_is_exclusive', $ride->return_is_exclusive) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="return_exclusive">Exclusive</label>
                    </div>
                            <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="return_is_exclusive" id="return_shared" value="0" {{ old('return_is_exclusive', $ride->return_is_exclusive) == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="return_shared">Shared</label>
                    </div>
                </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                <div class="mb-3">
                    <label for="return_station_location_map_url" class="form-label">Return Station Location Google Maps Link (optional)</label>
                    <input type="url" class="form-control" id="return_station_location_map_url" name="return_station_location_map_url" value="{{ old('return_station_location_map_url', $ride->return_station_location_map_url) }}" placeholder="https://maps.google.com/...">
                </div>
                    </div>
                    <div class="col-md-6">
                <div class="mb-3">
                    <label for="return_destination_map_url" class="form-label">Return Destination Google Maps Link (optional)</label>
                    <input type="url" class="form-control" id="return_destination_map_url" name="return_destination_map_url" value="{{ old('return_destination_map_url', $ride->return_destination_map_url) }}" placeholder="https://maps.google.com/...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-5 py-2"><i class="fas fa-save me-2"></i>Update Ride</button>
            </div>
        </form>
    </div>
</div>

<style>
.card-header {
    border-radius: 12px 12px 0 0 !important;
}
.bg-light {
    background: #f8f9fa !important;
}
input, select, textarea, .form-control {
    border-radius: 8px !important;
}
.btn {
    border-radius: 8px;
    font-weight: 600;
}
hr {
    border-top: 2px solid #e0e0e0;
}
</style>

@endsection

@section('scripts')
<script>
function toggleReturnFields() {
    var twoWay = document.getElementById('two_way').checked;
    document.getElementById('return-fields').style.display = twoWay ? 'block' : 'none';
}
function toggleReturnPriceFields() {
    var returnExclusive = document.getElementById('return_exclusive').checked;
    document.getElementById('return-price-per-person-field').style.display = returnExclusive ? 'none' : 'block';
    document.getElementById('return-exclusive-price-field').style.display = returnExclusive ? 'block' : 'none';
}
function syncReturnDestination() {
    var station = document.getElementById('station_location').value;
    document.getElementById('return_destination_display').value = station;
    document.getElementById('return_destination').value = station;
}
document.getElementById('station_location').addEventListener('input', syncReturnDestination);
document.addEventListener('DOMContentLoaded', function() {
    toggleReturnFields();
    toggleReturnPriceFields();
    document.getElementById('one_way').addEventListener('change', toggleReturnFields);
    document.getElementById('two_way').addEventListener('change', toggleReturnFields);
    document.getElementById('return_exclusive').addEventListener('change', toggleReturnPriceFields);
    document.getElementById('return_shared').addEventListener('change', toggleReturnPriceFields);
    syncReturnDestination();
});
</script>
@endsection 