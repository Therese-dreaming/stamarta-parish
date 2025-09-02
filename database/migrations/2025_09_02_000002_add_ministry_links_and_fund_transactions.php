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
        // Link services and bookings to ministries
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedBigInteger('ministry_id')->nullable()->after('service_type');
            $table->foreign('ministry_id')->references('id')->on('ministries')->nullOnDelete();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('ministry_id')->nullable()->after('service_id');
            $table->foreign('ministry_id')->references('id')->on('ministries')->nullOnDelete();
        });

        // Append-only ledger for ministry funds
        Schema::create('ministry_fund_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ministry_id');
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 12, 2);
            $table->string('description')->nullable();
            $table->string('reference_no')->nullable()->unique();
            $table->nullableMorphs('source'); // source_type, source_id
            $table->unsignedBigInteger('reversal_of_transaction_id')->nullable();
            $table->unsignedBigInteger('entered_by_user_id');
            $table->unsignedBigInteger('approved_by_user_id')->nullable();
            $table->timestamps();

            $table->foreign('ministry_id')->references('id')->on('ministries')->cascadeOnDelete();
            $table->foreign('reversal_of_transaction_id')->references('id')->on('ministry_fund_transactions')->nullOnDelete();
            $table->foreign('entered_by_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('approved_by_user_id')->references('id')->on('users')->nullOnDelete();

            $table->index(['ministry_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ministry_fund_transactions');

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'ministry_id')) {
                $table->dropForeign(['ministry_id']);
                $table->dropColumn('ministry_id');
            }
        });

        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'ministry_id')) {
                $table->dropForeign(['ministry_id']);
                $table->dropColumn('ministry_id');
            }
        });
    }
};


