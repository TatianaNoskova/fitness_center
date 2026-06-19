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
        
        $table->foreignId('sede_id')->constrained('sedes')->onDelete('cascade');
        
        $table->foreignId('entrenador_id')
              ->nullable()
              ->constrained('users') 
              ->onDelete('set null'); 

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
