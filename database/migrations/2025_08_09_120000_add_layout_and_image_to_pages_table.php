<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('layout')->nullable()->after('content');
            $table->foreignId('image_media_id')->nullable()->constrained('media')->nullOnDelete()->after('layout');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('image_media_id');
            $table->dropColumn('layout');
        });
    }
};


