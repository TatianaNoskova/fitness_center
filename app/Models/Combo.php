<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $fillable = ['nombre'];

    // Связь: один пакет содержит много услуг
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'combo_servicio');
    }
}
