<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagenController extends Controller
{
    // Mostrar todas las imágenes
    public function index()
    {
        return response()->json(Imagen::all(), 200);
    }

    // Guardar nueva imagen
public function store(Request $request)
{
    // Debug: Verificar qué está llegando en la petición
    \Log::info('Datos recibidos:', $request->all());
    \Log::info('Archivos recibidos:', $request->file() ? ['exists' => true] : ['exists' => false]);

    $validatedData = $request->validate([
        'imagen' => 'required|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'reporte_id' => 'required|exists:reportes,id'
    ]);

    // Debug: Verificar archivo después de validación
    if (!$request->hasFile('imagen')) {
        \Log::error('El archivo no se recibió correctamente');
        return response()->json(['error' => 'El archivo no se recibió correctamente'], 422);
    }

    $file = $request->file('imagen');
    $path = $file->store('reportes/' . $validatedData['reporte_id'], 'public');

    $imagen = Imagen::create([
        'reporte_id' => $validatedData['reporte_id'],
        'direccion' => $path,
        'nombre' => $file->getClientOriginalName()
    ]);

    return response()->json($imagen, 201);
}

    // Mostrar una imagen específica
    public function show($id)
    {
        $imagen = Imagen::findOrFail($id);
        return response()->json($imagen);
    }

    // Actualizar una imagen
    public function update(Request $request, $id)
    {
        $imagen = Imagen::findOrFail($id);

        $request->validate([
            'imagen' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'reporte_id' => 'sometimes|exists:reportes,id'
        ]);

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            
            // Borrar archivo anterior
            if ($imagen->direccion && Storage::disk('public')->exists($imagen->direccion)) {
                Storage::disk('public')->delete($imagen->direccion);
            }

            // Guardar nuevo archivo
            $path = $file->store('imagenes', 'public');
            $imagen->update([
                'direccion' => $path,
                'nombre' => $file->getClientOriginalName() // Actualizar nombre
            ]);
        }

        if ($request->has('reporte_id')) {
            $imagen->reporte_id = $request->reporte_id;
            $imagen->save();
        }

        return response()->json($imagen);
    }

    // Eliminar una imagen
    public function destroy($id)
    {
        $imagen = Imagen::findOrFail($id);

        // Eliminar archivo del filesystem
        if ($imagen->direccion && Storage::disk('public')->exists($imagen->direccion)) {
            Storage::disk('public')->delete($imagen->direccion);
        }

        $imagen->delete();

        return response()->json(['message' => 'Imagen eliminada']);
    }

    // Descargar imagen
    public function download($id)
    {
        $imagen = Imagen::findOrFail($id);
        
        if (!Storage::disk('public')->exists($imagen->direccion)) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $imagen->direccion, 
            $imagen->nombre // Usar el nombre original para la descarga
        );
    }
}