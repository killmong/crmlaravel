<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            // ── Contact Info ──────────────────────────────────────────
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();

            // ── Lead Details ──────────────────────────────────────────
            $table->string('status')->default('new');
            // new | contacted | qualified | proposal | follow_up | converted | lost

            $table->string('source')->nullable();
            // website | referral | cold_call | social_media | email_campaign | other

            $table->string('priority')->default('medium');
            // low | medium | high

            $table->unsignedBigInteger('value')->nullable();   // deal value in ₹

            $table->text('notes')->nullable();

            $table->string('department')->nullable();          // credit | AR | AP

            // ── Ownership ─────────────────────────────────────────────
            $table->foreignId('user_id')                       // creator / owner
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('assigned_to')                   // re-assigned agent/TL
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // ── Conversion link ───────────────────────────────────────
            $table->foreignId('contact_id')                    // set when converted
                  ->nullable()
                  ->constrained('contacts')
                  ->nullOnDelete();

            $table->timestamp('converted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
