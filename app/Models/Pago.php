<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = ['fecha', 'monto', 'metodo_pago', 'estado', 'socio_id', 'plan_id'];

    /**
     * Связи (Relationships)
     */
    public function socio()
    {
        return $this->belongsTo(Socio::class, 'socio_id', 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * Методы из UML-диаграммы
     */

    // registrarPago()
    public static function registrarPago(array $data)
    {
        $data['fecha'] = now()->toDateString(); // Ставим текущую дату
        $data['estado'] = 'PENDIENTE'; // Изначально платеж ожидает проверки
        
        $pago = self::create($data);
        
        // Сразу валидируем платеж после создания
        $pago->validarPago();
        
        return $pago;
    }

    // actualizarPago()
    public function actualizarPago(array $data)
    {
        return $this->update($data);
    }

    // obtenerPago()
    public function obtenerPago()
    {
        return $this;
    }

    // validarPago(): Проверка и подтверждение платежа
    public function validarPago(): bool
    {
        // Простая валидация данных: сумма должна быть больше 0 и указан метод оплаты
        if ($this->monto > 0 && !empty($this->metodo_pago)) {
            $this->update(['estado' => 'PAGADO']);
            return true;
        }

        $this->update(['estado' => 'RECHAZADO']);
        return false;
    }
}
