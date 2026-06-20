<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripcions'; 

    protected $fillable = ['fecha_inscripcion', 'estado', 'socio_id', 'clase_id'];

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'socio_id', 'user_id');
    }

    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    

    // registrarInscripcion()
    public static function registrarInscripcion(array $data)
    {
        // Перед записью проверяем, есть ли места через метод модели Clase!
        $clase = Clase::find($data['clase_id']);
        
        if ($clase && $clase->consultarDisponibilidad()) {
            $data['estado'] = 'CONFIRMADA';
            return self::create($data);
        }

        throw new \Exception("No hay cupos disponibles para esta clase.");
    }

    // cancelarInscripcion()
    public function cancelarInscripcion()
    {
        return $this->update(['estado' => 'CANCELADA']);
    }

    // confirmarInscripcion()
    public function confirmarInscripcion()
    {
        return $this->update(['estado' => 'CONFIRMADA']);
    }

    // obtenerInscripcion()
    public function obtenerInscripcion()
    {
        return $this;
    }
}
