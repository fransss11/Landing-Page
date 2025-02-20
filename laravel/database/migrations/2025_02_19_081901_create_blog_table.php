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
        Schema::create('blog', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 1000)->nullable();
            $table->string('category')->nullable();
            $table->string('descrip', 10000)->nullable();
            $table->string('img', 100)->nullable();
            $table->string('url')->nullable();
            $table->string('date', 100)->nullable();
            $table->string('status', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog');
    }
};
