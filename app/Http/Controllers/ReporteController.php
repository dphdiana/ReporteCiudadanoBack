<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;


class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $reportes = Reporte::with(['categoria', 'estado'])->get();

    return response()->json([
        'mensaje' => 'Lista de reportes',
        'reportes' => $reportes
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
        public function store(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'usuario_id' => 'required|exists:users,id',
            'ubicacion' => 'required|string',
            'estado_id' => 'required|exists:estados,id',

        ]);

        // Crear el reporte
        $reporte = Reporte::create($validated);

        return response()->json([
            'mensaje' => 'Reporte creado con éxito',
            'reporte' => $reporte
        ], 201);
    }

    /**
     * Display the specified resource.
     */
        public function show($id)
    {
    $reporte = Reporte::find($id);

    if (!$reporte) {
        return response()->json([
            'mensaje' => 'Reporte no encontrado.'
        ], 404);
    }

    return response()->json($reporte, 200);
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
    $reporte = Reporte::find($id);

    if (!$reporte) {
        return response()->json([
            'mensaje' => 'Reporte no encontrado.'
        ], 404);
    }

    // Validar que solo venga el estado_id
    $request->validate([
        'estado_id' => 'required|integer|exists:estados,id',
    ]);

    $reporte->estado_id = $request->estado_id;
    $reporte->save();

    return response()->json([
        'mensaje' => 'Estado del reporte actualizado correctamente.',
        'reporte' => $reporte
    ], 200);
}


    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    try {
        \DB::transaction(function () use ($id) {
            $reporte = Reporte::findOrFail($id);
            
            // Eliminará imágenes (con archivos) y luego el reporte
            $reporte->delete();
        });
        
        return response()->json([
            'mensaje' => 'Reporte y todas sus imágenes fueron eliminados completamente',
            'eliminado' => true
        ], 200);
        
    } catch (\Exception $e) {
        \Log::error('Error al eliminar reporte: ' . $e->getMessage());
        
        return response()->json([
            'mensaje' => 'Ocurrió un error al eliminar el reporte',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
