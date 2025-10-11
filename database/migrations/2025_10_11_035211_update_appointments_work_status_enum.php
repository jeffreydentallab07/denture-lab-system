<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE appointments MODIFY COLUMN work_status ENUM('pending', 'in-progress', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE appointments MODIFY COLUMN work_status ENUM('pending', 'in-progress', 'completed') NOT NULL DEFAULT 'pending'");
    }
};
