<?php

namespace Database\Seeders;

use App\Models\Rider;
use App\Models\RefillingStationOwner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RiderSeeder extends Seeder
{
    public function run()
    {
        $ownerId = RefillingStationOwner::first()->id ?? 1;
        
        Rider::create([
            'owner_id' => $ownerId,
            'name' => 'Rider Name',
            'email' => 'rider@example.com',
            'phone' => '1234567890',
            'password' => Hash::make('mypassword')
        ]);
    }
}