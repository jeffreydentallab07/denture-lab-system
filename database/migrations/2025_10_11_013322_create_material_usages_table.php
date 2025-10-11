<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('material_usages', function (Blueprint $table) {
            $table->id('usage_id');
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('material_id');
            $table->integer('quantity_used');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('appointment_id')->references('appointment_id')->on('appointments')->onDelete('cascade');
            $table->foreign('material_id')->references('material_id')->on('materials')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_usages');
    }
};
