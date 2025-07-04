<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'driver_license_file',
        'vehicle_registration_file',
        'insurance_certificate_file',
        'vehicle_photo_1',
        'vehicle_photo_2',
        'vehicle_photo_3',
        'terms_accepted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 