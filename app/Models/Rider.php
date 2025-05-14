<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rider extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $table = 'riders';
    protected $fillable = [
      'owner_id','name','email','phone','password',
    ];
    protected $hidden = ['password'];
}
