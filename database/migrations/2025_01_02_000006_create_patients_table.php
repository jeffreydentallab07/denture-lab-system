<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id('patient_id');
            $table->unsignedBigInteger('clinic_id');
            $table->unsignedBigInteger('dentist_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();

            $table->foreign('clinic_id')->references('clinic_id')->on('clinics')->onDelete('cascade');
            $table->foreign('dentist_id')->references('dentist_id')->on('dentists')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
