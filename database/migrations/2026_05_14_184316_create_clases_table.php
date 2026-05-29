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
        Schema::create('clases', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->time('hora');
            $table->integer('capacidad');
            
            // Связи
            $table->foreignId('sede_id')->constrained('sedes')->onDelete('cascade');
            // Ссылаемся на таблицу users, так как тренер — это пользователь с ролью ENTRENADOR
            $table->foreignId('entrenador_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clases');
    }
};
