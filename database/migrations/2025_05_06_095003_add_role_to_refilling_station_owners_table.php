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
        Schema::table('refilling_station_owners', function (Blueprint $table) {
            $table->enum('role', ['owner', 'rider'])->default('owner')->after('status');
        });
    }
    
    public function down()
    {
        Schema::table('refilling_station_owners', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
    
};
