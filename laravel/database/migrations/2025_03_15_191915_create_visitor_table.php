<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visitor', function (Blueprint $table) {
            // Gunakan increments dengan nama kolom id_visitor (auto increment primary key)
            $table->increments('id_visitor');
            
            // Kolom ip_address, VARCHAR(45) dengan collation utf8mb4_0900_ai_ci dan boleh null
            $table->string('ip_address', 45)->nullable();
            
            // Kolom visit_date, tipe DATE dan boleh null
            $table->date('visit_date')->nullable();
            
            // Kolom user_agent, VARCHAR(255) dengan collation utf8mb4_0900_ai_ci dan boleh null
            $table->string('user_agent', 255)->nullable();
            
            // Kolom browser, VARCHAR(255) dengan collation utf8mb4_0900_ai_ci dan boleh null
            $table->string('browser', 255)->nullable();
            
            // Kolom device, VARCHAR(255) dengan collation utf8mb4_0900_ai_ci dan boleh null
            $table->string('device', 255)->nullable();
            
            // Set charset dan collation untuk tabel
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
        });
        
        // Atur nilai auto-increment awal ke 20, sesuai DDL yang diberikan
        DB::statement("ALTER TABLE visitor AUTO_INCREMENT = 20");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor');
    }
};
