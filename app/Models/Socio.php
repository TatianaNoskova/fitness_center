<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Services\CuotaContext;
use App\Patterns\Strategy\SocioNormalStrategy;
use App\Patterns\Strategy\SocioEstudianteStrategy;
use App\Patterns\Strategy\SocioVipStrategy;

class Socio extends Model
{
    use HasFactory;

    protected $table = 'socios'; 

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $fillable = ['user_id', 'sede_id', 'fecha_alta', 'estado', 'plan_id', 'categoria'];

    /**
     * El metodo del patron Strategy para el calculo del precio
     */
    public function obtenerPrecioCuota(): float
{
    if (!$this->plan) {
        return 0.0;
    }

    $precioBase = (float) $this->plan->precio;

    $estrategias = [
        'NORMAL'     => \App\Patterns\Strategy\SocioNormalStrategy::class,
        'ESTUDIANTE' => \App\Patterns\Strategy\SocioEstudianteStrategy::class,
        'VIP'        => \App\Patterns\Strategy\SocioVipStrategy::class,
    ];

    $claseEstrategia = $estrategias[strtoupper($this->categoria)] ?? \App\Patterns\Strategy\SocioNormalStrategy::class;

    $cuotaContext = app(\App\Services\CuotaContext::class);
    $cuotaContext->setStrategy(new $claseEstrategia());

    return $cuotaContext->calcularCuota($precioBase);
}

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function clases()
    {
        return $this->belongsToMany(Clase::class, 'inscripcions', 'socio_id', 'clase_id')
                    ->withPivot('asistencia', 'estado', 'fecha_inscripcion')
                    ->withTimestamps();
    }

    
    public function inscribirseAClase(Clase $clase)
    {
        if (strtoupper($this->estado) !== 'ACTIVO') {
            throw new \Exception("No puedes inscribirte, tu membresía no está activa.");
        }

        if ($this->clases()->where('clase_id', $clase->id)->exists()) {
            throw new \Exception("Ya estás inscrito en esta clase.");
        }

        $actuales = $clase->socios()->count();
        if ($actuales >= $clase->capacidad) {
            throw new \Exception("La clase ya está llena.");
        }

        $this->clases()->attach($clase->id, [
            'fecha_inscripcion' => now()->toDateString(),
            'estado'            => 'CONFIRMADA',
            'asistencia'        => 'PENDIENTE'
        ]);
    }

    public function cancelarInscripcion(Clase $clase)
    {
        if (!$this->clases()->where('clase_id', $clase->id)->exists()) {
            throw new \Exception("No estás inscrito en esta clase.");
        }

        $this->clases()->detach($clase->id);

        \App\Models\Inscripcion::where('clase_id', $clase->id)
            ->where('socio_id', $this->user_id)
            ->delete();
    }
}