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
        
        // Связь с филиалом (оставляем как у тебя)
        $table->foreignId('sede_id')->constrained('sedes')->onDelete('cascade');
        
        // Идеальная и безопасная связь напрямую с таблицей users
        $table->foreignId('entrenador_id')
              ->nullable()
              ->constrained('users') // Ссылаемся на главную таблицу пользователей
              ->onDelete('set null'); // Если пользователя удалят, занятие останется без тренера

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
