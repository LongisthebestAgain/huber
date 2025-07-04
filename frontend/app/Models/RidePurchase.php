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
        'price_per_seat',
        'total_price',
        'payment_status',
        'payment_method',
        'special_requests',
        'trip_type',
        'passenger_details',
        'contact_phone',
    ];

    protected $casts = [
        'passenger_details' => 'array',
        'price_per_seat' => 'decimal:2',
        'total_price' => 'decimal:2',
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
