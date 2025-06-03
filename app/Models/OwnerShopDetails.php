<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerShopDetails extends Model
{
    use HasFactory;

    protected $table = 'owner_shop_details';

    protected $fillable = [
        'owner_id',
        'delivery_time_slots',
        'collection_days',
        'has_regular_gallon',
        'regular_gallon_price',
        'has_dispenser_gallon',
        'dispenser_gallon_price',
        'borrow_price',
        'total_orders',
        'pending_orders',
        'rider_count',
        'monthly_earnings',
    ];

    protected $casts = [
        'delivery_time_slots' => 'array',
        'collection_days' => 'array',
        'has_regular_gallon' => 'boolean',
        'has_dispenser_gallon' => 'boolean',
        'monthly_earnings' => 'array',
    ];

    /**
     * Get the owner that this shop details belongs to
     */
    public function owner()
    {
        return $this->belongsTo(RefillingStationOwner::class, 'owner_id');
    }

    /**
     * Get the available delivery time options
     */
    public static function getDeliveryTimeOptions()
    {
        return [
            '7AM', '8AM', '9AM', '10AM', '11AM',
            '12PM', '1PM', '2PM', '3PM', '4PM'
        ];
    }

    /**
     * Get the available collection day options
     */
    public static function getCollectionDayOptions()
    {
        return [
            'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'
        ];
    }

    /**
     * Get product types with their default prices
     */
    public static function getProductTypes()
    {
        return [
            'regular_gallon' => [
                'name' => 'Regular Gallon',
                'default_price' => 50.00
            ],
            'dispenser_gallon' => [
                'name' => 'Dispenser Gallon',
                'default_price' => 50.00
            ]
        ];
    }
}
