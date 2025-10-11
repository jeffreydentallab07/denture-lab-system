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
        if (!Schema::hasColumn('deliveries', 'delivery_date')) {
            Schema::table('deliveries', function (Blueprint $table) {
                $table->timestamp('delivery_date')->nullable()->after('delivery_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('deliveries', 'delivery_date')) {
            Schema::table('deliveries', function (Blueprint $table) {
                $table->dropColumn('delivery_date');
            });
        }
    }
};
