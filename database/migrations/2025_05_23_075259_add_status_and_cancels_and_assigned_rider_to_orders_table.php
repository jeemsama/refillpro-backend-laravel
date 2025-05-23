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
        Schema::table('orders', function (Blueprint $table) {
            // 1) status (defaults to “pending”)
            $table
              ->string('status')
              ->default('pending')
              ->after('total')
              ->comment('pending, cancelled_by_customer, cancelled_by_owner, accepted, declined');

            // 2) if customer cancels, store their reason
            $table
              ->text('cancel_reason_customer')
              ->nullable()
              ->after('status');

            // 3) if owner cancels or declines, store their reason
            $table
              ->text('cancel_reason_owner')
              ->nullable()
              ->after('cancel_reason_customer');

            // 4) when owner accepts, optionally assign a rider
            $table
              ->foreignId('assigned_rider_id')
              ->nullable()
              ->constrained('riders')
              ->onDelete('set null')
              ->after('cancel_reason_owner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // drop FK first
            $table->dropForeign(['assigned_rider_id']);
            // then drop the columns
            $table->dropColumn([
              'assigned_rider_id',
              'cancel_reason_owner',
              'cancel_reason_customer',
              'status',
            ]);
        });
    }
};
