<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Imagen extends Model
{
    use HasFactory;

    protected $table = 'imagenes';

    protected $fillable = [
        'reporte_id',
        'direccion',
        'nombre'
    ];

    public function reporte()
    {
        return $this->belongsTo(Reporte::class, 'reporte_id');
    }

    protected static function booted()
    {
        static::deleting(function ($imagen) {
            // Eliminar el archivo físico si existe
            if ($imagen->direccion && Storage::disk('public')->exists($imagen->direccion)) {
                Storage::disk('public')->delete($imagen->direccion);
            }
        });

        static::deleted(function ($imagen) {
            // Opcional: eliminar directorio si queda vacío
            $directorio = dirname($imagen->direccion);
            $archivosEnDirectorio = count(Storage::disk('public')->files($directorio));
            
            if ($archivosEnDirectorio === 0) {
                Storage::disk('public')->deleteDirectory($directorio);
            }
        });
    }
}