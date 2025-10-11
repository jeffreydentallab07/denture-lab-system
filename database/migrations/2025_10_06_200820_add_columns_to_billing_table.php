<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id')->after('id');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'paid', 'partial'])->default('unpaid');
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();

            // Foreign key
            $table->foreign('appointment_id')
                ->references('appointment_id')
                ->on('appointments')
                ->onDelete('cascade');

            // Index
            $table->index('appointment_id');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropIndex(['appointment_id']);
            $table->dropIndex(['payment_status']);
            $table->dropColumn([
                'appointment_id',
                'total_amount',
                'payment_status',
                'payment_method',
                'notes'
            ]);
        });
    }
};
