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
        Schema::create('owner_shop_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('refilling_station_owners')->onDelete('cascade');
            $table->json('delivery_time_slots')->nullable();
            $table->json('collection_days')->nullable();
            $table->boolean('has_regular_gallon')->default(false);
            $table->decimal('regular_gallon_price', 8, 2)->default(50.00);
            $table->boolean('has_dispenser_gallon')->default(false);
            $table->decimal('dispenser_gallon_price', 8, 2)->default(50.00);
            $table->integer('total_orders')->default(0);
            $table->integer('pending_orders')->default(0);
            $table->integer('rider_count')->default(0);
            $table->json('monthly_earnings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_shop_details');
    }
};
