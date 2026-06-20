<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Patterns\Composite\ClaseComposite;
use App\Patterns\Composite\ClaseLeaf;

class Combo extends Model
{
    protected $fillable = ['nombre', 'descuento'];

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'combo_servicio');
    }

    public function getPrecioCalculadoAttribute(): float
    {
        $composite = new ClaseComposite($this->nombre, $this->descuento ?? 0);

        if ($this->servicios) {
            foreach ($this->servicios as $servicio) {
                $composite->agregar(new ClaseLeaf($servicio));
            }
        }

        return $composite->getPrecio();
    }
}
