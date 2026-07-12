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
        Schema::table('events', function (Blueprint $table) {
            $table->integer('team_size')->nullable()->after('registration_type');
        });

        Schema::table('team_join_requests', function (Blueprint $table) {
            $table->enum('type', ['request', 'invite'])->default('request')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('team_size');
        });

        Schema::table('team_join_requests', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
