<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->unsignedBigInteger('case_order_id');
            $table->unsignedBigInteger('technician_id');
            $table->dateTime('schedule_datetime');
            $table->text('purpose')->nullable();
            $table->enum('work_status', ['pending', 'in-progress', 'completed'])->default('pending');
            $table->timestamps();

            $table->foreign('case_order_id')->references('co_id')->on('case_orders')->onDelete('cascade');
            $table->foreign('technician_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
