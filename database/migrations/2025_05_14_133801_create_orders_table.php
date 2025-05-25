<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shop_id')->constrained('owner_shop_details')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            $table->string('time_slot');
            $table->text('message')->nullable();
            $table->unsignedInteger('regular_count');
            $table->unsignedInteger('dispenser_count');
            $table->boolean('borrow');
            $table->boolean('swap');
            $table->decimal('total', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};