<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria_id',
        'usuario_id',
        'ubicacion',
        'estado_id',
    ];

    // Relaciones

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function imagenes()
    {
        return $this->hasMany(Imagen::class, 'idReporte');
    }
}
