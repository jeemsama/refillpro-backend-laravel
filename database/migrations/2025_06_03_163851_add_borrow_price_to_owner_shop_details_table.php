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
        Schema::table('owner_shop_details', function (Blueprint $table) {
            $table->decimal('borrow_price', 8, 2)->default(0.00)->after('dispenser_gallon_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('owner_shop_details', function (Blueprint $table) {
            $table->dropColumn('borrow_price');
        });
    }
};
