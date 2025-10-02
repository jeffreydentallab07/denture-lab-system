<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_case_order', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_case_order', function (Blueprint $table) {
            //public function up(): void
{
    Schema::table('tbl_case_order', function (Blueprint $table) {
        $table->unsignedBigInteger('technician_id')->nullable()->after('id');
        $table->foreign('technician_id')->references('id')->on('users')->onDelete('set null');
    });
}

        });
    }
};
