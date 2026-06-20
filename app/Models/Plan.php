<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'duracion', 'estado'];

    
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'plan_id');
    }

    

    // registrarPlan()
    public static function registrarPlan(array $data)
    {
        $data['estado'] = 'ACTIVO'; 
        return self::create($data);
    }

    // actualizarPlan()
    public function actualizarPlan(array $data)
    {
        return $this->update($data);
    }

    // desactivarPlan()
    public function desactivarPlan()
    {
        return $this->update(['estado' => 'INACTIVO']);
    }

    // obtenerPlan()
    public function obtenerPlan()
    {
        return $this;
    }
}