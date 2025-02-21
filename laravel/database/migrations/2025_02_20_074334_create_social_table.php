<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('social_table', function (Blueprint $table) {
            $table->id();
            $table->string('facebook', 255)->nullable();
            $table->string('twiter', 255)->nullable();
            $table->string('instagram', 255)->nullable();
            $table->string('whatsapp', 13)->nullable();
            $table->string('linkedin', 255)->nullable();
            $table->timestamps(); // created_at & updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_table');
    }
};
