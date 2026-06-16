<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Patterns\Composite\ClaseComposite;
use App\Patterns\Composite\ClaseLeaf;

class Combo extends Model
{
    protected $fillable = ['nombre', 'descuento'];

    // Связь: один пакет содержит много услуг
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'combo_servicio');
    }

    public function getPrecioCalculadoAttribute(): float
    {
        // Создаем объект Composite, передавая имя и скидку из текущей модели
        $composite = new ClaseComposite($this->nombre, $this->descuento ?? 0);

        // Наполняем его "листьями" (услугами), если они загружены
        if ($this->servicios) {
            foreach ($this->servicios as $servicio) {
                $composite->agregar(new ClaseLeaf($servicio));
            }
        }

        // Возвращаем итоговую цену со скидкой
        return $composite->getPrecio();
    }
}
