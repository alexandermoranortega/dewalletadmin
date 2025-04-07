<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Cambiar el encoding de la tabla completa
        DB::statement('ALTER TABLE vendedor_password_reset_codes CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        
        // 2. Cambiar el encoding de cada columna (solo si es necesario)
        DB::statement('ALTER TABLE vendedor_password_reset_codes MODIFY email VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE vendedor_password_reset_codes MODIFY code VARCHAR(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Revertir a la configuración anterior
        DB::statement('ALTER TABLE vendedor_password_reset_codes CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        DB::statement('ALTER TABLE vendedor_password_reset_codes MODIFY email VARCHAR(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        DB::statement('ALTER TABLE vendedor_password_reset_codes MODIFY code VARCHAR(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }
};