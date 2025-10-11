<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('delivery_id');

            // Changed from unsignedInteger to unsignedBigInteger
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->foreign('appointment_id')
                ->references('appointment_id')
                ->on('appointments')
                ->onDelete('set null');

            $table->unsignedBigInteger('rider_id')->nullable();
            $table->foreign('rider_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->enum('delivery_status', ['ready to deliver', 'in transit', 'delivered', 'cancelled'])
                ->default('ready to deliver');
            $table->timestamp('delivery_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
