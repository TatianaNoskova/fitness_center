<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sede extends Model
{
    use HasFactory;

    // Указываем, какие поля можно заполнять массово
    protected $fillable = ['nombre', 'direccion', 'telefono', 'email'];

    /**
     * Связи (Relationships)
     */
    public function socios()
    {
        // Агрегация: один филиал имеет много клиентов
        return $this->hasMany(Socio::class, 'sede_id');
    }

    public function clases()
    {
        // Связь с занятиями (тоже есть на твоей диаграмме)
        return $this->hasMany(Clase::class, 'sede_id');
    }

    /**
     * Методы из диаграммы классов
     */

    // registrarSede(): Создание нового филиала
    public static function registrarSede(array $data)
    {
        return self::create($data);
    }

    // actualizarSede(): Обновление данных
    public function actualizarSede(array $data)
    {
        return $this->update($data);
    }

    // eliminarSede(): Удаление филиала
    public function eliminarSede()
    {
        return $this->delete();
    }

    // obtenerSede(): Получение информации о филиале
    public function obtenerSede()
    {
        // Возвращаем текущий объект (или можно настроить форматированный вывод)
        return $this;
    }
}