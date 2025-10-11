<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id('material_id'); // or just id() if you prefer
            $table->string('material_name');
            $table->text('description')->nullable();
            $table->string('unit')->nullable(); // e.g., 'kg', 'pcs', 'liters'
            $table->integer('quantity')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->enum('status', ['available', 'low stock', 'out of stock'])->default('available');
            $table->timestamps();

            // Indexes
            $table->index('material_name');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
