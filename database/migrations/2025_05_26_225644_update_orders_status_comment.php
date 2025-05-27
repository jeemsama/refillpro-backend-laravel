<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("
          ALTER TABLE `orders`
          MODIFY COLUMN `status`
          VARCHAR(255) NOT NULL
          DEFAULT 'pending'
          COMMENT 'pending, cancelled_by_customer, cancelled_by_owner, accepted, declined, completed'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement("
          ALTER TABLE `orders`
          MODIFY COLUMN `status`
          VARCHAR(255) NOT NULL
          DEFAULT 'pending'
          COMMENT 'pending, cancelled_by_customer, cancelled_by_owner, accepted, declined'
        ");
    }
};
