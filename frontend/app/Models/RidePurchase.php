<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RidePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'user_id',
        'number_of_seats',
        'total_price',
        'payment_status',
        'payment_method',
        'card_last_four',
        'card_type',
        'special_requests',
        'trip_type',
        'passenger_details',
        'selected_seats',
        'seats_confirmed',
        'contact_phone',
        'booking_reference',
        'booking_date',
        'booking_time',
    ];

    protected $casts = [
        'passenger_details' => 'array',
        'selected_seats' => 'array',
        'seats_confirmed' => 'boolean',
        'total_price' => 'decimal:2',
        'booking_date' => 'date',
        'booking_time' => 'datetime:H:i',
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
