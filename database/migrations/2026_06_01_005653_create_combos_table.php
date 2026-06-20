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
        
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
          
            $table->integer('descuento')->default(0); 
            $table->timestamps();
        });

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
        Schema::dropIfExists('combo_servicio');
        Schema::dropIfExists('combos');
    }
};