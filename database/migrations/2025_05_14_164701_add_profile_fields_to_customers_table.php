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
        Schema::table('customers', function (Blueprint $table) {
            // add the columns
            $table->string('email')->unique()->after('id');
            $table->string('name')->nullable()->after('email');
            $table->string('phone')->nullable()->after('name');
            $table->string('address')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // drop them on rollback
            $table->dropColumn(['email', 'name', 'phone', 'address']);
        });
    }
};
