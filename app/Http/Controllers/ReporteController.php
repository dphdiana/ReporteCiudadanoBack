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
            'foto' => 'nullable|image|max:2048', // admite imagen, max 2MB
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

    public function actualarEstado(Request $request, $id)
    {
         $request->validate([
        'estado' => 'required|in:pendiente,en_proceso,resuelto,rechazado'
    ]);

    $reporte = Reporte::findOrFail($id);

    if (!auth()->user()->isAdmin()) {
        return response()->json(['error' => 'No autorizado'], 403);
    }

    $reporte->estado = $request->estado;
    $reporte->save();

    return response()->json([
        'message' => 'Estado actualizado correctamente',
        'reporte' => $reporte
    ]);
    }
}