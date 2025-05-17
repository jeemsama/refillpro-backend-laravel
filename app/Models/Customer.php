<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['email','name','phone','address'];
    protected $hidden   = [];  // no password here
}
