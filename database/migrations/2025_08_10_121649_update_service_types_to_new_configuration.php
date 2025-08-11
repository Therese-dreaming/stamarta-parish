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
        // Update service types to match the new configuration
        DB::table('services')
            ->where('name', 'Solo Baptism')
            ->update(['service_type' => 'solo_baptism']);

        DB::table('services')
            ->where('name', 'Group Baptism')
            ->update(['service_type' => 'group_baptism']);

        DB::table('services')
            ->where('name', 'Wedding')
            ->update(['service_type' => 'wedding']);

        DB::table('services')
            ->where('name', 'Blessing')
            ->update(['service_type' => 'blessing']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert service types back to old configuration
        DB::table('services')
            ->where('name', 'Solo Baptism')
            ->update(['service_type' => 'baptism']);

        DB::table('services')
            ->where('name', 'Group Baptism')
            ->update(['service_type' => 'baptism']);

        DB::table('services')
            ->where('name', 'Wedding')
            ->update(['service_type' => 'wedding']);

        DB::table('services')
            ->where('name', 'Blessing')
            ->update(['service_type' => 'blessing']);
    }
};
