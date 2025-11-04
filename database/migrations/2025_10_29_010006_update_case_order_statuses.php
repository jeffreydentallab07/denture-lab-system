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
        // First, update ALL existing statuses to ensure they're compatible
        // Map old 'completed' to 'under review'
        DB::statement("UPDATE `case_orders` SET `status` = 'under review' WHERE `status` = 'completed'");

        // Update any other incompatible statuses to 'pending' as a fallback
        DB::statement("UPDATE `case_orders` SET `status` = 'pending' WHERE `status` NOT IN ('pending', 'in progress', 'under review')");

        // Now modify the ENUM column using raw SQL (more reliable than ->change())
        DB::statement("ALTER TABLE `case_orders` MODIFY `status` ENUM('pending', 'in progress', 'under review', 'adjustment requested', 'revision in progress', 'completed') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update new statuses back to old compatible ones
        DB::statement("UPDATE `case_orders` SET `status` = 'completed' WHERE `status` IN ('under review', 'adjustment requested', 'revision in progress', 'completed')");

        // Revert to original ENUM
        DB::statement("ALTER TABLE `case_orders` MODIFY `status` ENUM('pending', 'in progress', 'completed') NOT NULL DEFAULT 'pending'");
    }
};
