<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Clase extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'fecha', 'hora', 'capacidad', 'sede_id', 'entrenador_id'];

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function entrenador()
    {
        return $this->belongsTo(Entrenador::class, 'entrenador_id', 'user_id');
    }

    public function inscripciones()
    {
        // Composición
        return $this->hasMany(Inscripcion::class, 'clase_id');
    }

    public function socios()
    {
        return $this->belongsToMany(Socio::class, 'inscripcions', 'clase_id', 'socio_id')
                    ->withPivot('asistencia', 'estado', 'fecha_inscripcion')
                    ->withTimestamps();
    }


    // crearClase()
    public static function crearClase(array $data)
    {
        return self::create($data);
    }

    // actualizarClase()
    public function actualizarClase(array $data)
    {
        return $this->update($data);
    }

    // cancelarClase()
    public function cancelarClase()
    {
        
        return $this->delete();
    }

    // consultarDisponibilidad
    public function consultarDisponibilidad(): bool
    {

        $anotados = $this->inscripciones()->count();
        
        return $anotados < $this->capacidad;
    }

    public static function esEntrenadorOcupado($entrenadorId, $fecha, $hora, $ignoreClaseId = null)
    {
        $query = self::where('entrenador_id', $entrenadorId)
                     ->where('fecha', $fecha)
                     ->where('hora', $hora);

        if ($ignoreClaseId) {
            $query->where('id', '!=', $ignoreClaseId);
        }

        return $query->exists();
    }




}