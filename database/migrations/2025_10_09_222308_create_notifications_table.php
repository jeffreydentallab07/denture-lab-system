<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Admin who will see it
            $table->string('type'); // 'case_order_pending', 'delivery_assigned', etc
            $table->string('title');
            $table->text('message');
            $table->string('link')->nullable(); // URL to redirect to
            $table->unsignedBigInteger('reference_id')->nullable(); // case_order_id, appointment_id, etc
            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
