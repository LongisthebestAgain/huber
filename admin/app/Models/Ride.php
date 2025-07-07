<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'station_location',
        'destination',
        'date',
        'time',
        'available_seats',
        'is_exclusive',
        'is_two_way',
        'return_station_location',
        'return_destination',
        'return_date',
        'return_time',
        'return_available_seats',
        'return_is_exclusive',
        'station_location_map_url',
        'destination_map_url',
        'return_station_location_map_url',
        'return_destination_map_url',
        'go_to_price_per_person',
        'return_price_per_person',
        'go_to_exclusive_price',
        'return_exclusive_price',
        'go_completion_status',
        'return_completion_status',
        'go_completed_at',
        'return_completed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'return_date' => 'date',
        'return_time' => 'datetime:H:i',
        'go_to_price_per_person' => 'decimal:2',
        'return_price_per_person' => 'decimal:2',
        'go_to_exclusive_price' => 'decimal:2',
        'return_exclusive_price' => 'decimal:2',
        'go_completed_at' => 'datetime',
        'return_completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'user_id')->where('role', 'driver');
    }

    public function ridePurchases()
    {
        return $this->hasMany(RidePurchase::class);
    }

    public function reviews()
    {
        return $this->hasMany(RideReview::class);
    }
} 