<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'is_verified',
        'license_number', 'license_expiry', 'vehicle_model', 'vehicle_year', 'vehicle_color', 'license_plate', 'vehicle_seats',
        'profile_picture', 'address', 'date_of_birth', 'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_verified' => 'boolean',
        ];
    }

    public function driverDocuments()
    {
        return $this->hasOne(DriverDocument::class);
    }

    public function rides()
    {
        return $this->hasMany(Ride::class);
    }

    public function ridePurchases()
    {
        return $this->hasMany(RidePurchase::class);
    }
}
