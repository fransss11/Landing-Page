<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('info', function (Blueprint $table) {
            $table->id('id_info'); // Primary key
            $table->string('lokasi', 1000)->nullable();
            $table->string('gmail', 255)->nullable();
            $table->string('maps_url', 2000)->nullable(); // Tambahkan kolom maps_url
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('info');
    }
};