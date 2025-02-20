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
        Schema::create('package', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 50);
            $table->string('type', 20);
            $table->string('location', 30);
            $table->string('price', 50);
            $table->string('day', 50);
            $table->string('img', 100);
            $table->string('Itinerary', 8000);
            $table->string('Inclusions', 8000);
            $table->string('NOTE', 8000);
            $table->string('status', 10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package');
    }
};
