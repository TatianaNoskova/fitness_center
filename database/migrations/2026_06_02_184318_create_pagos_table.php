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
            Schema::create('pagos', function (Blueprint $table) {
                $table->id();
                $table->date('fecha');
                $table->decimal('monto', 10, 2);
                $table->string('metodo_pago', 45)->nullable(); 
                $table->string('estado', 45)->nullable(); 
                
                // Relaciones
                $table->foreignId('socio_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('plan_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('combo_id')->nullable()->constrained('combos')->onDelete('set null');
                $table->foreignId('servicio_id')->nullable()->constrained('servicios')->onDelete('set null');         
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
