<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    // Защищаем поле id, остальные разрешаем заполнять
    protected $fillable = ['nombre', 'descripcion', 'precio', 'duracion', 'estado'];

    /**
     * Связи (Relationships)
     */
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'plan_id');
    }

    /**
     * Методы из UML-диаграммы
     */

    // registrarPlan()
    public static function registrarPlan(array $data)
    {
        $data['estado'] = 'ACTIVO'; // По умолчанию новый план активен
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