<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Reporte extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria_id',
        'usuario_id',
        'ubicacion',
        'estado_id',
    ];

    protected $appends = [
        'fecha_creacion_formateada',
        'imagenes_urls'
    ];

    // Relaciones
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

public function imagenes()
{
    return $this->hasMany(Imagen::class, 'reporte_id');
}

    // Accesores
    public function getFechaCreacionFormateadaAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getImagenesUrlsAttribute()
    {
        return $this->imagenes->map(function ($imagen) {
            return [
                'url' => Storage::url($imagen->direccion),
                'nombre' => $imagen->nombre
            ];
        });
    }

    // Eventos del modelo
protected static function booted()
{
    static::deleting(function ($reporte) {
        // Eliminar imágenes relacionadas (activará los eventos en Imagen)
        $reporte->imagenes()->each(function ($imagen) {
            $imagen->delete(); // Esto activará deleting/deleted en Imagen
        });
    });
}
}