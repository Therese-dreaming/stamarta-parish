<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ministry_members', function (Blueprint $table) {
            $table->unique(['ministry_id', 'user_id'], 'ministry_members_ministry_user_unique');
        });
    }

    public function down(): void
    {
        Schema::table('ministry_members', function (Blueprint $table) {
            $table->dropUnique('ministry_members_ministry_user_unique');
        });
    }
};


