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
        // Таблица самих пакетов
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            // ИСПРАВЛЕНО: Добавили поле скидки в процентах (по умолчанию 0, т.е. без скидки)
            $table->integer('descuento')->default(0); 
            $table->timestamps();
        });

        // Таблица-связка между пакетами и услугами (Pivot table)
        Schema::create('combo_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_id')->constrained()->onDelete('cascade');
            $table->foreignId('servicio_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ИСПРАВЛЕНО: Дропаем обе таблицы в правильном порядке (сначала связующую из-за внешних ключей)
        Schema::dropIfExists('combo_servicio');
        Schema::dropIfExists('combos');
    }
};