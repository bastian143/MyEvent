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
        // Alter enum using raw statement since doctrine/dbal might have issues with enums
        DB::statement("ALTER TABLE event_registrations MODIFY COLUMN status ENUM('registered', 'pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE event_registrations MODIFY COLUMN status ENUM('registered', 'cancelled') DEFAULT 'registered'");
    }
};
