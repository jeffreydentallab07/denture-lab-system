<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clinic_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->string('link')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->foreign('clinic_id')->references('clinic_id')->on('clinics')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('clinic_notifications');
    }
};
