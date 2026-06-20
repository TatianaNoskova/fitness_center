<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = ['nombre', 'precio'];

    
    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'combo_servicio', 'servicio_id', 'combo_id');
    }
}