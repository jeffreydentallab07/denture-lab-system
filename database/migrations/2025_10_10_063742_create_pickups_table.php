<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pickups', function (Blueprint $table) {
            $table->id('pickup_id');
            $table->unsignedBigInteger('case_order_id');
            $table->unsignedBigInteger('rider_id');
            $table->date('pickup_date');
            $table->string('pickup_address');
            $table->enum('status', ['pending', 'picked-up', 'delivered'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->foreign('case_order_id')->references('co_id')->on('case_orders')->onDelete('cascade');
            $table->foreign('rider_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pickups');
    }
};
