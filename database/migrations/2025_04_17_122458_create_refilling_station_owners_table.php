<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('refilling_station_owners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('dti_permit_path')->nullable();
            $table->string('business_permit_path')->nullable();
            
            // Shop Info
            $table->string('shop_name');
            $table->string('address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
    
            // Gallon Types
            $table->boolean('has_regular_gallon')->default(false);
            $table->decimal('regular_gallon_price', 8, 2)->nullable();
                    
            $table->boolean('has_dispenser_gallon')->default(false);
            $table->decimal('dispenser_gallon_price', 8, 2)->nullable();
                    
            $table->boolean('has_small_gallon')->default(false);
            $table->decimal('small_gallon_price', 8, 2)->nullable();

    
            // Delivery Time Slots (morning & afternoon as JSON or booleans)
            $table->json('delivery_time_slots')->nullable();

            
            //terms and conditions

            $table->boolean('agreed_to_terms')->default(false);
    
            // Status
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
            $table->text('decline_reason')->nullable();
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refilling_station_owners');
    }
};
