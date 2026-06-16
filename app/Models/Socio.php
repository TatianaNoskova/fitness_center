<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Services\CuotaContext;
use App\Patterns\Strategy\SocioNormalStrategy;
use App\Patterns\Strategy\SocioEstudianteStrategy;
use App\Patterns\Strategy\SocioVipStrategy;

class Socio extends Model
{
    use HasFactory;

    protected $table = 'socios'; 

    // ВАЖНО: Говорим Laravel, что первичный ключ называетсяuser_id, а не id
    protected $primaryKey = 'user_id';

    // Отключаем автоинкремент для этой модели, так как user_id приходит из таблицы users
    public $incrementing = false;

    protected $fillable = ['user_id', 'sede_id', 'fecha_alta', 'estado', 'plan_id', 'categoria'];

    /**
     * Метод паттерна Strategy для расчета стоимости
     */
    public function obtenerPrecioCuota(): float
{
    // Если плана нет, считать нечего
    if (!$this->plan) {
        return 0.0;
    }

    $precioBase = (float) $this->plan->precio;

    // Маппинг: Категория из базы -> Класс твоей стратегии
    $estrategias = [
        'NORMAL'     => \App\Patterns\Strategy\SocioNormalStrategy::class,
        'ESTUDIANTE' => \App\Patterns\Strategy\SocioEstudianteStrategy::class,
        'VIP'        => \App\Patterns\Strategy\SocioVipStrategy::class,
    ];

    // Определяем класс стратегии, если в базе что-то чужое — берем Normal
    $claseEstrategia = $estrategias[strtoupper($this->categoria)] ?? \App\Patterns\Strategy\SocioNormalStrategy::class;

    // Вызываем контекст из папки Services
    $cuotaContext = app(\App\Services\CuotaContext::class);
    $cuotaContext->setStrategy(new $claseEstrategia());

    // Вызываем метод calcular(), который написан в твоих стратегиях
    return $cuotaContext->calcularCuota($precioBase);
}

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Связь с филиалом
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    // Связь с планом
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * ИСПРАВЛЕННАЯ СВЯЗЬ: Указываем внешние ключи вручную
     */
    public function clases()
    {
        return $this->belongsToMany(Clase::class, 'clase_socio', 'socio_id', 'clase_id')
                    ->withPivot('asistencia')
                    ->withTimestamps();
    }

    /**
     * Бизнес-метод из UML для записи клиента на занятие
     */
    public function inscribirseAClase(Clase $clase)
    {
        // Проверяем, активен ли абонемент клиента
        if (strtoupper($this->estado) !== 'ACTIVO') {
            throw new \Exception("No puedes inscribirte, tu membresía no está activa.");
        }

        // Проверяем, не записан ли уже клиент на это занятие
        if ($this->clases()->where('clase_id', $clase->id)->exists()) {
            throw new \Exception("Ya estás inscrito en esta clase.");
        }

        // Проверяем вместимость занятия
        $actuales = $clase->socios()->count();
        if ($actuales >= $clase->capacidad) {
            throw new \Exception("La clase ya está llena.");
        }

        // Записываем клиента (добавляем строчку в сводную таблицу clase_socio)
        $this->clases()->attach($clase->id);
    }

    /**
     * Бизнес-метод для отмены записи на занятие
     */
    public function cancelarInscripcion(Clase $clase)
    {
        // Проверяем, записан ли вообще клиент на это занятие
        if (!$this->clases()->where('clase_id', $clase->id)->exists()) {
            throw new \Exception("No estás inscrito en esta clase.");
        }

        // Удаляем запись из сводной таблицы clase_socio
        $this->clases()->detach($clase->id);
    }
}