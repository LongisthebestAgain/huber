@extends('layouts.ride-management')

@section('title', 'Create Ride - Hubber')

@section('main')
<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="h4 mb-4">Create a New Ride</h2>
        <form method="POST" action="{{ route('driver.rides.store') }}">
            @csrf
            <div class="mb-3">
                <label for="station_location" class="form-label">Station Location</label>
                <input type="text" class="form-control" id="station_location" name="station_location" value="{{ old('station_location') }}" required>
            </div>
            <div class="mb-3">
                <label for="destination" class="form-label">Destination</label>
                <input type="text" class="form-control" id="destination" name="destination" value="{{ old('destination') }}" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
            </div>
            <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <input type="time" class="form-control" id="time" name="time" value="{{ old('time') }}" required>
            </div>
            <div class="mb-3">
                <label for="available_seats" class="form-label">Available Seats</label>
                <input type="number" class="form-control" id="available_seats" name="available_seats" min="1" max="20" value="{{ old('available_seats') }}" required>
            </div>
            <div class="mb-3" id="go-to-price-field">
                <label for="go_to_price_per_person" class="form-label">Go To Price Per Person</label>
                <input type="number" step="0.01" min="0" class="form-control" id="go_to_price_per_person" name="go_to_price_per_person" value="{{ old('go_to_price_per_person') }}">
            </div>
            <div class="mb-3">
                <label for="station_location_map_url" class="form-label">Station Location Google Maps Link (optional)</label>
                <input type="url" class="form-control" id="station_location_map_url" name="station_location_map_url" value="{{ old('station_location_map_url') }}" placeholder="https://maps.google.com/...">
            </div>
            <div class="mb-3">
                <label for="destination_map_url" class="form-label">Destination Google Maps Link (optional)</label>
                <input type="url" class="form-control" id="destination_map_url" name="destination_map_url" value="{{ old('destination_map_url') }}" placeholder="https://maps.google.com/...">
            </div>
            <div class="mb-3">
                <label class="form-label">Type</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_exclusive" id="exclusive" value="1" {{ old('is_exclusive') == '1' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="exclusive">Exclusive</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_exclusive" id="shared" value="0" {{ old('is_exclusive') == '0' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="shared">Shared</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Trip Type</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_two_way" id="one_way" value="0" {{ old('is_two_way') == '0' ? 'checked' : '' }} required onclick="toggleReturnFields()">
                    <label class="form-check-label" for="one_way">One Way</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_two_way" id="two_way" value="1" {{ old('is_two_way') == '1' ? 'checked' : '' }} required onclick="toggleReturnFields()">
                    <label class="form-check-label" for="two_way">Two Way (Return)</label>
                </div>
            </div>
            <div id="return-fields" style="display: none;">
                <hr>
                <h5 class="mb-3">Return Trip Details</h5>
                <div class="mb-3">
                    <label for="return_station_location" class="form-label">Return Station Location</label>
                    <input type="text" class="form-control" id="return_station_location" name="return_station_location" value="{{ old('return_station_location') }}">
                </div>
                <div class="mb-3">
                    <label for="return_destination" class="form-label">Return Destination</label>
                    <input type="text" class="form-control" id="return_destination_display" value="{{ old('station_location') }}" readonly>
                    <input type="hidden" id="return_destination" name="return_destination" value="{{ old('station_location') }}">
                </div>
                <div class="mb-3">
                    <label for="return_date" class="form-label">Return Date</label>
                    <input type="date" class="form-control" id="return_date" name="return_date" value="{{ old('return_date') }}">
                </div>
                <div class="mb-3">
                    <label for="return_time" class="form-label">Return Time</label>
                    <input type="time" class="form-control" id="return_time" name="return_time" value="{{ old('return_time') }}">
                </div>
                <div class="mb-3">
                    <label for="return_available_seats" class="form-label">Return Available Seats</label>
                    <input type="number" class="form-control" id="return_available_seats" name="return_available_seats" min="1" max="20" value="{{ old('return_available_seats') }}">
                </div>
                <div class="mb-3">
                    <label for="return_station_location_map_url" class="form-label">Return Station Location Google Maps Link (optional)</label>
                    <input type="url" class="form-control" id="return_station_location_map_url" name="return_station_location_map_url" value="{{ old('return_station_location_map_url') }}" placeholder="https://maps.google.com/...">
                </div>
                <div class="mb-3">
                    <label for="return_destination_map_url" class="form-label">Return Destination Google Maps Link (optional)</label>
                    <input type="url" class="form-control" id="return_destination_map_url" name="return_destination_map_url" value="{{ old('return_destination_map_url') }}" placeholder="https://maps.google.com/...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Return Type</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="return_is_exclusive" id="return_exclusive" value="1" {{ old('return_is_exclusive') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="return_exclusive">Exclusive</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="return_is_exclusive" id="return_shared" value="0" {{ old('return_is_exclusive') == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="return_shared">Shared</label>
                    </div>
                </div>
                <div class="mb-3" id="return-price-field" style="display: none;">
                    <label for="return_price_per_person" class="form-label">Return Price Per Person</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="return_price_per_person" name="return_price_per_person" value="{{ old('return_price_per_person') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Create Ride</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleReturnFields() {
    var twoWay = document.getElementById('two_way').checked;
    document.getElementById('return-fields').style.display = twoWay ? 'block' : 'none';
    document.getElementById('return-price-field').style.display = twoWay ? 'block' : 'none';
}
function syncReturnDestination() {
    var station = document.getElementById('station_location').value;
    document.getElementById('return_destination_display').value = station;
    document.getElementById('return_destination').value = station;
}
document.addEventListener('DOMContentLoaded', function() {
    toggleReturnFields();
    document.getElementById('one_way').addEventListener('change', toggleReturnFields);
    document.getElementById('two_way').addEventListener('change', toggleReturnFields);
    document.getElementById('station_location').addEventListener('input', syncReturnDestination);
    syncReturnDestination();
});
</script>
@endsection 