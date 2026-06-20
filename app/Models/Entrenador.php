<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entrenador extends Model
{
    use HasFactory;

    protected $table = 'entrenadors'; 

    protected $primaryKey = 'user_id'; 
    public $incrementing = false; 

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