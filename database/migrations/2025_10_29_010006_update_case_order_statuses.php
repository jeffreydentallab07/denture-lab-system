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
        // Update existing 'completed' statuses to 'under review'
        DB::table('case_orders')
            ->where('status', 'completed')
            ->update(['status' => 'under review']);

        // Modify the status column to include new statuses
        Schema::table('case_orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'in progress',
                'under review',
                'adjustment requested',
                'revision in progress',
                'completed'
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'in progress',
                'completed'
            ])->default('pending')->change();
        });
    }
};
