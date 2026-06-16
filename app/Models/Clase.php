<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Clase extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'fecha', 'hora', 'capacidad', 'sede_id', 'entrenador_id'];

    /**
     * Связи (Relationships)
     */
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
        // Композиция: у одного занятия много записей
        return $this->hasMany(Inscripcion::class, 'clase_id');
    }

    public function socios()
    {
        // Оставляем только имя таблицы и внешние ключи. Laravel сам поймет первичные ключи моделей!
        return $this->belongsToMany(Socio::class, 'clase_socio', 'clase_id', 'socio_id')
                    ->withPivot('asistencia') // Без этого тренер не сможет читать и менять статус!
                    ->withTimestamps();
    }

    /**
     * Методы из UML-диаграммы
     */

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
        // Можно либо удалить, либо поменять статус. Пока сделаем удаление, как в CRUD
        return $this->delete();
    }

    // consultarDisponibilidad(): Логика проверки мест
    public function consultarDisponibilidad(): bool
    {
        // Считаем сколько человек уже записалось на это занятие
        $anotados = $this->inscripciones()->count();
        
        // Если записавшихся меньше, чем емкость (capacidad), значит места есть
        return $anotados < $this->capacidad;
    }




}