<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entrenador extends Model
{
    use HasFactory;

    // Указываем родную таблицу
    protected $table = 'entrenadors'; 

    // Так как стандартного 'id' нет, настраиваем кастомный первичный ключ
    protected $primaryKey = 'user_id'; 
    public $incrementing = false; // Исправлено: убраны круглые скобки

    protected $fillable = ['user_id', 'sede_id', 'especialidad', 'estado'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function obedienceSede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }
}