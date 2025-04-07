<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vendedor_password_reset_codes', function (Blueprint $table) {
            $table->id();
            $table->string('email', 191)->index(); // Longitud específica para compatibilidad
            $table->string('code', 6); // Longitud fija para códigos de 6 dígitos
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at');
            
            // Especificar collation UTF-8 explícitamente
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('vendedor_password_reset_codes');
    }
};