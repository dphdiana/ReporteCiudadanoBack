<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Crear una nueva categoría
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
        ]);

        $categoria = Categoria::create(['nombre' => $request->nombre]);

        return response()->json([
            'mensaje' => 'Categoría creada con éxito',
            'categoria' => $categoria
        ], 201);
    }

    /**
     * Obtener una categoría por ID
     */
    public function show($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['mensaje' => 'Categoría no encontrada'], 404);
        }

        return response()->json($categoria, 200);
    }

    /**
     * Actualizar una categoría
     */
    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['mensaje' => 'Categoría no encontrada'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $id,
        ]);

        $categoria->nombre = $request->nombre;
        $categoria->save();

        return response()->json([
            'mensaje' => 'Categoría actualizada con éxito',
            'categoria' => $categoria
        ]);
    }

    /**
     * Eliminar una categoría
     */
    public function destroy($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['mensaje' => 'Categoría no encontrada'], 404);
        }

        $categoria->delete();

        return response()->json(['mensaje' => 'Categoría eliminada con éxito'], 200);
    }
}

