<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure the users.role enum includes 'ministry_head'
        DB::statement("ALTER TABLE users MODIFY role ENUM('user','staff','priest','ministry_head','admin') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous enum without 'ministry_head'
        DB::statement("ALTER TABLE users MODIFY role ENUM('user','staff','priest','admin') NOT NULL DEFAULT 'user'");
    }
};


