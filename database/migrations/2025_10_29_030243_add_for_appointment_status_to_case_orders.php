<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE case_orders MODIFY COLUMN status ENUM(
            'pending',
            'for appointment',
            'in progress',
            'under review',
            'adjustment requested',
            'revision in progress',
            'completed'
        ) DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE case_orders MODIFY COLUMN status ENUM(
            'pending',
            'in progress',
            'under review',
            'adjustment requested',
            'revision in progress',
            'completed'
        ) DEFAULT 'pending'");
    }
};
