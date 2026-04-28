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
    $table->string('carrier_id')->unique();
    $table->string('name')->nullable();
    $table->string('dot_number')->nullable();
    $table->string('mc_number')->nullable();
    $table->string('status')->nullable();
    $table->longText('raw_xml')->nullable();
    $table->timestamp('synced_at')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('carriers');
    }
};
