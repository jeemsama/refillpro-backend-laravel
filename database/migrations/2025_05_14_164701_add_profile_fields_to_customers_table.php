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
        if (!Schema::hasColumn('customers', 'email')) {
            $table->string('email')->unique()->after('id');
        }
        if (!Schema::hasColumn('customers', 'name')) {
            $table->string('name')->nullable()->after('email');
        }
        if (!Schema::hasColumn('customers', 'phone')) {
            $table->string('phone')->nullable()->after('name');
        }
        if (!Schema::hasColumn('customers', 'address')) {
            $table->string('address')->nullable()->after('phone');
        }
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
