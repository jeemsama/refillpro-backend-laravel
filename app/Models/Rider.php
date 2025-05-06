<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{

    protected $fillable = ['owner_id', 'name', 'email', 'phone', 'password'];
    protected $hidden = ['password'];
    public function owner()
    {
        return $this->belongsTo(RefillingStationOwner::class, 'owner_id');
    }

}
