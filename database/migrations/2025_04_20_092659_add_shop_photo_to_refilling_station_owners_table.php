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
        Schema::table('refilling_station_owners', function (Blueprint $table) {
            $table->string('shop_photo')->nullable()->after('business_permit_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refilling_station_owners', function (Blueprint $table) {
            $table->dropColumn('shop_photo');
        });
    }
};
