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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ridePurchases()
    {
        return $this->hasMany(RidePurchase::class);
    }
} 