<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'nombre',
    'apellido',
    'dni',
    'telefono',
    'email',
    'password',
    'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Проверка роли пользователя (для RBAC)
     */
    public function hasRole(string $role): bool
    {
        // Приводим к нижнему регистру, чтобы избежать ошибок с 'Socio' vs 'socio'
        return strtolower($this->rol) === strtolower($role);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('administrador');
    }

    public function isCoach(): bool
    {
        return $this->hasRole('coach') || $this->hasRole('entrenador');
    }

    public function isSocio(): bool
    {
        return $this->hasRole('socio');
    }
}
