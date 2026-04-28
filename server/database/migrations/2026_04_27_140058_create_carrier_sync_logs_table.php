<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrier_sync_logs', function (Blueprint $table) {
            $table->id();

            $table->string('rmis_id');                // which carrier
            $table->string('mc_number')->nullable();  // for readability in logs

            $table->enum('action', [
                'fetched',    // pulled from Delta API
                'expanded',   // called Expanded Carrier API
                'cleared',    // cleared from Delta queue
                'detached',   // carrier no longer belongs to client
                'failed',     // something went wrong
            ]);

            $table->text('message')->nullable();       // error message or notes
            $table->string('api_timestamp')->nullable(); // timestamp from XML header

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carrier_sync_logs');
    }
};
