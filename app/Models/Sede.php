<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sede extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'direccion', 'telefono', 'email'];

   
    public function socios()
    {
       
        return $this->hasMany(Socio::class, 'sede_id');
    }

    public function clases()
    {
        return $this->hasMany(Clase::class, 'sede_id');
    }


    public static function registrarSede(array $data)
    {
        return self::create($data);
    }

    public function actualizarSede(array $data)
    {
        return $this->update($data);
    }

    public function eliminarSede()
    {
        return $this->delete();
    }

    public function obtenerSede()
    {
    
        return $this;
    }
}