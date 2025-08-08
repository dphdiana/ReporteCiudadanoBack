<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    // Campos que se pueden llenar con asignación masiva
    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo_usuario', // admin o ciudadano
    ];

    // Campos ocultos al devolver el modelo como JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relación: un usuario puede tener muchos reportes
     */
    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class);
    }

    /**
     * Autenticación usando el campo "correo" en lugar de "email"
     */

    public function isAdmin()
    {
        return $this->tipo_usuario === 'admin';
    }
    public function username()
    {
        return 'email';
    }
}
