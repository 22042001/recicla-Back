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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('nombre'); // Campo 'nombre' para el nombre del usuario
            $table->string('email')->unique(); // Campo 'email' único
            $table->string('password'); // Campo 'password' para la contraseña
            $table->string('teléfono')->nullable(); // Campo 'teléfono' opcional
            $table->string('ubicación')->nullable(); // Campo 'ubicación' opcional
            $table->timestamp('email_verified_at')->nullable(); // Verificación de correo opcional
            $table->rememberToken(); // Token para la opción 'remember me'
            $table->timestamps(); // Campos 'created_at' y 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
