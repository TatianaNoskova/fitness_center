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
    Schema::create('clase_socio', function (Blueprint $table) {
        $table->id();
        
        // 1. Связь с таблицей clases (там обычный id)
        $table->foreignId('clase_id')
              ->constrained('clases')
              ->onDelete('cascade');
              
        // 2. Связь с таблицей socios: 
        // Создаем колонку socio_id тип BigInteger, но ссылаемся на 'user_id' в таблице 'socios'!
        $table->unsignedBigInteger('socio_id');
        
        $table->foreign('socio_id')
              ->references('user_id') // Указываем точное имя первичного ключа таблицы socios
              ->on('socios')
              ->onDelete('cascade');

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
};
