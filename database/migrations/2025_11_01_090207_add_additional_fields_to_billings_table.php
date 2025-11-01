<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->text('additional_details')->nullable()->after('total_amount');
            $table->decimal('additional_amount', 10, 2)->default(0)->after('additional_details');
        });
    }

    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn(['additional_details', 'additional_amount']);
        });
    }
};
