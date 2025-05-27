<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use App\Models\OwnerShopDetails;

class Order extends Model
{
    use HasFactory; 
        // which fields can be mass‐assigned
    protected $fillable = [
      'shop_id','customer_id','time_slot','message',
      'ordered_by','phone',
      'regular_count','dispenser_count','borrow','swap','total',
      'status','cancel_reason_customer','cancel_reason_owner','assigned_rider_id',
      'latitude', 'longitude',
    ];

        // cast booleans & decimals
    protected $casts = [
      'borrow'  => 'boolean',
      'swap'    => 'boolean',
      'total'   => 'decimal:2',
      'assigned_rider_id' => 'integer',
    ];

        public function shopDetails()
    {
      return $this->belongsTo(OwnerShopDetails::class, 'shop_id');
    }

        public function customer()
    {
      return $this->belongsTo(Customer::class);
    }

      public function rider()
    {
      return $this->belongsTo(Rider::class, 'assigned_rider_id');
    }
}
