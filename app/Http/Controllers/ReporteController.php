<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reporte;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        // Guardar foto si existe
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('reportes', 'public');
        }

        $reporte = Reporte::create([
            'titulo' => $request->titulo,
            'user_id' => Auth::id(),
            'categoria' => $request->categoria,
            'descripcion' => $request->descripcion,
            'foto' => $fotoPath,
            'estado' => 'pendiente',
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        // Agregar URL completa a la respuesta
        $reporte->foto_url = $fotoPath ? asset(Storage::url($fotoPath)) : null;

        return response()->json([
            'message' => 'Reporte creado correctamente',
            'reporte' => $reporte
        ], 201);
    }

    public function index()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $reportes = Reporte::with('usuario')->latest()->get();

        // Agregar URL completa para cada reporte
        $reportes->map(function ($r) {
            $r->foto_url = $r->foto ? asset(Storage::url($r->foto)) : null;
            return $r;
        });

        return response()->json($reportes);
    }

    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_proceso,resuelto,rechazado'
        ]);

        $reporte = Reporte::findOrFail($id);
        $reporte->estado = $request->estado;
        $reporte->save();

        $reporte->foto_url = $reporte->foto ? asset(Storage::url($reporte->foto)) : null;

        return response()->json([
            'message' => 'Estado actualizado correctamente',
            'reporte' => $reporte
        ]);
    }

    public function destroy($id)
    {
        $reporte = Reporte::findOrFail($id);

        // Eliminar foto del almacenamiento
        if ($reporte->foto) {
            Storage::disk('public')->delete($reporte->foto);
        }

        $reporte->delete();

        return response()->json(['message' => 'Reporte eliminado correctamente']);
    }
}
