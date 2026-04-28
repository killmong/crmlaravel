<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            // ── Core Info ─────────────────────────────────────────────
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();

            // ── Classification ────────────────────────────────────────
            $table->string('type')->default('lead');
            // lead | customer | partner | vendor

            $table->string('status')->default('active');
            // active | inactive | archived

            $table->string('source')->nullable();
            // website | referral | cold_call | social_media | email_campaign | other

            $table->string('priority')->default('medium');
            // low | medium | high

            $table->string('department')->nullable();

            // ── Financials ────────────────────────────────────────────
            $table->unsignedBigInteger('deal_value')->nullable();
            $table->string('currency')->default('INR');

            // ── Extra ─────────────────────────────────────────────────
            $table->text('notes')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable()->default('India');
            $table->string('pincode')->nullable();

            // ── Ownership ─────────────────────────────────────────────
            $table->foreignId('user_id')                       // owner/creator
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // ── Origin ────────────────────────────────────────────────
            $table->foreignId('lead_id')                       // originating lead
                  ->nullable()
                  ->constrained('leads')
                  ->nullOnDelete();

            $table->timestamp('lead_converted_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
