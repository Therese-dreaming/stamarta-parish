<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ministry_budget_request_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('budget_request_id');
            $table->string('path');
            $table->string('original_name');
            $table->unsignedBigInteger('uploaded_by');
            $table->timestamps();

            $table->foreign('budget_request_id')->references('id')->on('ministry_budget_requests')->cascadeOnDelete();
            $table->foreign('uploaded_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ministry_budget_request_files');
    }
};


