<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = ['nombre', 'precio'];

    /**
     * Связь: Одна услуга может входить во множество разных комбо-пакетов.
     * ИСПРАВЛЕНО: Имя таблицы теперь строго совпадает с миграцией 'combo_servicio'
     */
    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'combo_servicio', 'servicio_id', 'combo_id');
    }
}