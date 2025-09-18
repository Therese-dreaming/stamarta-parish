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
        Schema::create('parish_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, number, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default parish budget setting
        DB::table('parish_settings')->insert([
            [
                'key' => 'parish_total_budget',
                'value' => '0.00',
                'type' => 'number',
                'description' => 'Total parish budget allocation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'parish_name',
                'value' => 'St. Martha Parish',
                'type' => 'string',
                'description' => 'Official parish name',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parish_settings');
    }
};
