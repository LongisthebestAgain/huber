<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'user_id',
        'ride_purchase_id',
        'overall_rating',
        'driver_rating',
        'vehicle_rating',
        'punctuality_rating',
        'safety_rating',
        'comfort_rating',
        'review_text',
        'trip_type',
        'status',
    ];

    protected $casts = [
        'overall_rating' => 'integer',
        'driver_rating' => 'integer',
        'vehicle_rating' => 'integer',
        'punctuality_rating' => 'integer',
        'safety_rating' => 'integer',
        'comfort_rating' => 'integer',
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ridePurchase()
    {
        return $this->belongsTo(RidePurchase::class);
    }

    // Helper method to calculate average rating
    public function getAverageRatingAttribute()
    {
        $ratings = [
            $this->driver_rating,
            $this->vehicle_rating,
            $this->punctuality_rating,
            $this->safety_rating,
            $this->comfort_rating
        ];
        
        return round(array_sum($ratings) / count($ratings), 1);
    }

    // Helper method to get star display
    public function getStarsDisplayAttribute()
    {
        $rating = $this->overall_rating;
        $stars = '';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }
        
        return $stars;
    }
}
