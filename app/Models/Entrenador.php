<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entrenador extends Model
{
    use HasFactory;

    // Указываем его родную таблицу
    protected $table = 'entrenadors'; 

    protected $fillable = ['user_id', 'sede_id', 'especialidad', 'estado'];

    // Связь с базовым пользователем (у каждого тренера есть профиль юзера)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function obedienceSede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }
}