@extends('layouts.ride-management')

@section('title', 'My Rides - Hubber')

@section('main')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h2 class="h4 mb-4">My Rides</h2>
        <p class="text-muted">This page lists all rides you have created.</p>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Your Rides</h5>
        @php $rides = $user->rides()->latest()->get(); @endphp
        @if($rides->isEmpty())
            <div class="alert alert-info">You have not created any rides yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Seats</th>
                            <th>Type</th>
                            <th>Trip</th>
                            <th>Go To Price</th>
                            <th>Return Price</th>
                            <th>Return Trip</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rides as $i => $ride)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $ride->station_location }}</td>
                            <td>{{ $ride->destination }}</td>
                            <td>{{ $ride->date }}</td>
                            <td>{{ $ride->time }}</td>
                            <td>{{ $ride->available_seats }}</td>
                            <td>{{ $ride->is_exclusive ? 'Exclusive' : 'Shared' }}</td>
                            <td>{{ $ride->is_two_way ? 'Two Way' : 'One Way' }}</td>
                            <td>
                                @if($ride->is_exclusive)
                                    @if($ride->go_to_exclusive_price !== null)
                                        ${{ number_format($ride->go_to_exclusive_price, 2) }} (Total)
                                    @else
                                        -
                                    @endif
                                @else
                                    @if($ride->go_to_price_per_person !== null)
                                        ${{ number_format($ride->go_to_price_per_person, 2) }}/person
                                    @else
                                        -
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($ride->is_two_way)
                                    @if($ride->return_is_exclusive)
                                        @if($ride->return_exclusive_price !== null)
                                            ${{ number_format($ride->return_exclusive_price, 2) }} (Total)
                                        @else
                                            -
                                        @endif
                                    @else
                                        @if($ride->return_price_per_person !== null)
                                            ${{ number_format($ride->return_price_per_person, 2) }}/person
                                        @else
                                            -
                                        @endif
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($ride->is_two_way)
                                    <div><b>From:</b> {{ $ride->return_station_location }}</div>
                                    <div><b>To:</b> {{ $ride->return_destination }}</div>
                                    <div><b>Date:</b> {{ $ride->return_date }}</div>
                                    <div><b>Time:</b> {{ $ride->return_time }}</div>
                                    <div><b>Seats:</b> {{ $ride->return_available_seats }}</div>
                                    <div><b>Type:</b> {{ $ride->return_is_exclusive === null ? '-' : ($ride->return_is_exclusive ? 'Exclusive' : 'Shared') }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                                <div class="mt-2">
                                    <a href="{{ route('driver.rides.edit', $ride->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection 