<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{

    use HasFactory;

    // Campos que se pueden llenar con asignación masiva
    protected $fillable = [
        'titulo',
        'user_id', // ID del usuario que crea el reporte
        'foto', // URL de la foto del reporte
        'categoria', // categoría del reporte (basura, bache, etc.)
        'descripcion', // pendiente, en_proceso, resuelto
        
    ];

    /**
     * Relación: un reporte pertenece a un usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
