<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carriers', function (Blueprint $table) {
            $table->id();

            // Identifiers
            $table->string('rmis_id')->unique();
            $table->string('mc_number')->nullable();
            $table->string('dot_number')->nullable();
            $table->string('tax_id')->nullable();

            // Company Info
            $table->string('company_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('title')->nullable();

            // Address
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();

            // Contact
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();

            // Truckstop Specific
            $table->boolean('is_certified')->default(false); // isCertified node (required in UI)
            $table->string('status')->default('active');     // active | detached

            // Sync tracking
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carriers');
    }
};
