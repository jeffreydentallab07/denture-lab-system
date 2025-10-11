<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_orders', function (Blueprint $table) {
            $table->id('co_id');
            $table->unsignedBigInteger('clinic_id');
            $table->unsignedBigInteger('dentist_id')->nullable();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->string('case_type');
            $table->string('status')->default('initial');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('clinic_id')->references('clinic_id')->on('clinics')->onDelete('cascade');
            $table->foreign('dentist_id')->references('dentist_id')->on('dentists')->onDelete('set null');
            $table->foreign('patient_id')->references('patient_id')->on('patients')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('case_orders');
    }
};
