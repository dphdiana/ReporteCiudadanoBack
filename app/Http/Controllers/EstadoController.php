<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    /**
     * Crear un nuevo estado
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:estados,nombre',
        ]);

        $estado = Estado::create(['nombre' => $request->nombre]);

        return response()->json([
            'mensaje' => 'Estado creado con éxito',
            'estado' => $estado
        ], 201);
    }

    /**
     * Obtener un estado por ID
     */
    public function show($id)
    {
        $estado = Estado::find($id);

        if (!$estado) {
            return response()->json(['mensaje' => 'Estado no encontrado'], 404);
        }

        return response()->json($estado, 200);
    }

    /**
     * Actualizar un estado
     */
    public function update(Request $request, $id)
    {
        $estado = Estado::find($id);

        if (!$estado) {
            return response()->json(['mensaje' => 'Estado no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255|unique:estados,nombre,' . $id,
        ]);

        $estado->nombre = $request->nombre;
        $estado->save();

        return response()->json([
            'mensaje' => 'Estado actualizado con éxito',
            'estado' => $estado
        ]);
    }

    /**
     * Eliminar un estado
     */
    public function destroy($id)
    {
        $estado = Estado::find($id);

        if (!$estado) {
            return response()->json(['mensaje' => 'Estado no encontrado'], 404);
        }

        $estado->delete();

        return response()->json(['mensaje' => 'Estado eliminado con éxito'], 200);
    }
}
