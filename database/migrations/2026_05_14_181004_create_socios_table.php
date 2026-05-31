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
    Schema::create('socios', function (Blueprint $table) {
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('sede_id')->nullable()->constrained('sedes')->onDelete('set null');
        $table->foreignId('plan_id')->nullable()->constrained('plans', 'id')->onDelete('set null');
        
        // Добавляем категорию (статус) клиента: NORMAL, ESTUDIANTE или VIP
        $table->string('categoria', 20)->default('NORMAL');
        
        $table->date('fecha_alta');
        $table->string('estado', 45)->default('ACTIVO');
        $table->primary('user_id'); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
