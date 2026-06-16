<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Жестко задаем имя класса, которое ищет Laravel
class CreateClaseSocioTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clase_socio', function (Blueprint $table) {
            $table->id();
            
            // 1. Связь с таблицей clases
            $table->foreignId('clase_id')
                  ->constrained('clases')
                  ->onDelete('cascade');
                  
            // 2. Связь с таблицей socios (через ваш кастомный user_id)
            $table->unsignedBigInteger('socio_id');
            
            $table->foreign('socio_id')
                  ->references('user_id')
                  ->on('socios')
                  ->onDelete('cascade');

            // 3. Добавляем статус посещаемости
            $table->string('asistencia')->default('PENDIENTE');

            $table->timestamps();

            // Защита от дубликатов
            $table->unique(['clase_id', 'socio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clase_socio');
    }
}