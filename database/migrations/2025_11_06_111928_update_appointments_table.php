<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add new column
        Schema::table('appointments', function (Blueprint $table) {
            $table->date('estimated_date')->after('technician_id')->nullable();
        });

        // Copy data from schedule_datetime to estimated_date (date only)
        DB::statement('UPDATE appointments SET estimated_date = DATE(schedule_datetime)');

        // Make estimated_date not nullable
        Schema::table('appointments', function (Blueprint $table) {
            $table->date('estimated_date')->nullable(false)->change();
        });

        // Drop old column
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('schedule_datetime');
        });

        // Update work_status enum to include 'cancelled' and change default to 'in-progress'
        DB::statement("ALTER TABLE `appointments` MODIFY `work_status` ENUM('pending', 'in-progress', 'completed', 'cancelled') NOT NULL DEFAULT 'in-progress'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back schedule_datetime column
        Schema::table('appointments', function (Blueprint $table) {
            $table->dateTime('schedule_datetime')->after('technician_id')->nullable();
        });

        // Copy data from estimated_date back to schedule_datetime
        DB::statement('UPDATE appointments SET schedule_datetime = estimated_date');

        // Make schedule_datetime not nullable
        Schema::table('appointments', function (Blueprint $table) {
            $table->dateTime('schedule_datetime')->nullable(false)->change();
        });

        // Drop estimated_date
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('estimated_date');
        });

        // Revert work_status default to 'pending' and remove 'cancelled'
        DB::statement("ALTER TABLE `appointments` MODIFY `work_status` ENUM('pending', 'in-progress', 'completed') NOT NULL DEFAULT 'pending'");
    }
};
