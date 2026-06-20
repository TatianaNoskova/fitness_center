<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha', 
        'monto', 
        'metodo_pago', 
        'estado', 
        'socio_id', 
        'plan_id', 
        'combo_id', 
        'servicio_id', 
        'detalles'
    ];

    
    public function socio()
    {
        return $this->belongsTo(Socio::class, 'socio_id', 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    
    public function combo()
    {
        return $this->belongsTo(Combo::class, 'combo_id');
    }

    
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    
    public static function registrarPago(array $data)
    {
        $data['fecha'] = now()->toDateString();
        $data['estado'] = 'PENDIENTE';
        
        $pago = self::create($data);
        $pago->validarPago();
        
        return $pago;
    }

    public function actualizarPago(array $data)
    {
        return $this->update($data);
    }

    public function obtenerPago()
    {
        return $this;
    }

    public function validarPago(): bool
    {
        if ($this->monto > 0 && !empty($this->metodo_pago)) {
            $this->update(['estado' => 'PAGADO']);
            return true;
        }

        $this->update(['estado' => 'RECHAZADO']);
        return false;
    }
}