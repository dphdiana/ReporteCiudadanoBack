<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reporte;

use Illuminate\Support\Facades\Auth;


class ReporteController extends Controller
{
       public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'categoria' => 'required|string|in:vial,seguridad,servicios',
            'descripcion' => 'required|string',
            'foto' => 'nullable|image|max:2048',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric'
        ]);

        // Si la foto viene como archivo, la guardamos
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('reportes', 'public');
        }

        $reporte = Reporte::create([
            'titulo' => $request->titulo,
            'user_id' => Auth::id(), // el usuario autenticado
            'categoria' => $request->categoria,
            'descripcion' => $request->descripcion,
            'foto' => $fotoPath,
            'estado' => 'pendiente', // estado por defecto al crear un reporte
            'latitud' => $request->latitud,
            'longitud' => $request->longitud
        ]);

        return response()->json([
            'message' => 'Reporte creado correctamente',
            'reporte' => $reporte
        ], 201);
    }

    /**
     * Lista todos los reportes (opcional para admins)
     */
public function index()
{
    // Verifica si el usuario está autenticado Y es admin
    if (!auth()->check() || !auth()->user()->isAdmin()) {
        return response()->json(['message' => 'No autorizado'], 403);
    }

    $reportes = Reporte::with('usuario')->latest()->get();
    return response()->json($reportes);
}

   public function actualizarEstado(Request $request, $id)
{
    // Validar que el estado enviado sea uno permitido
    $request->validate([
        'estado' => 'required|in:pendiente,en_proceso,resuelto,rechazado'
    ]);

    // Buscar el reporte por ID
    $reporte = Reporte::findOrFail($id);

    // Actualizar el estado
    $reporte->estado = $request->estado;
    $reporte->save();

    return response()->json([
        'message' => 'Estado actualizado correctamente',
        'reporte' => $reporte
    ]);
}

}