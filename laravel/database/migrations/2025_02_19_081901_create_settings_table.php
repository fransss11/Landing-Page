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
        Schema::create('settings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('header_logo')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2', 20)->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('footer_desc', 5000)->nullable();
            $table->string('facebook', 2000)->nullable();
            $table->string('twitter', 2000)->nullable();
            $table->string('linkedin', 2000)->nullable();
            $table->string('instagram', 2000)->nullable();
            $table->string('youtube', 2000)->nullable();
            $table->string('address', 1000)->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pin')->nullable();
            $table->string('map', 3000)->nullable();
            $table->string('site_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
