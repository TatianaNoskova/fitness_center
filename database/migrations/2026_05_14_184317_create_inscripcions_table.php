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
        Schema::create('inscripcions', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_inscripcion');
            $table->string('estado', 45)->default('CONFIRMADA'); // Например: CONFIRMADA, CANCELADA
            
            // Связи
            $table->foreignId('socio_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('clase_id')->constrained('clases')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripcions');
    }
};
