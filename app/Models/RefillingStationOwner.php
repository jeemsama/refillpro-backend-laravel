<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;  
use Laravel\Sanctum\HasApiTokens;                       
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RefillingStationOwner extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'dti_permit_path',
        'business_permit_path',

        'shop_name',
        'address',
        'shop_photo',
        'latitude',
        'longitude',

        'has_regular_gallon',
        'regular_gallon_price',
        'has_dispenser_gallon',
        'dispenser_gallon_price',
        'has_small_gallon',
        'small_gallon_price',
        'delivery_time_slots',
        'agreed_to_terms',
        'status',
    ];

    protected $casts = [
        'delivery_time_slots' => 'array',
        'agreed_to_terms' => 'boolean',
        'has_regular_gallon' => 'boolean',
        'has_dispenser_gallon' => 'boolean',
        'has_small_gallon' => 'boolean',
    ];
    protected $hidden = [
        'password',
    ];
    
    public function riders()
    {
        return $this->hasMany(Rider::class, 'owner_id');
    }

}

