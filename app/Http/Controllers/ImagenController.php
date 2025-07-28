<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use Illuminate\Http\Request;

class ImagenController extends Controller
{
    public function index()
    {
        return Imagen::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'idReporte' => 'required|exists:reportes,id',
            'direccion' => 'required|string',
        ]);

        $imagen = Imagen::create($request->all());

        return response()->json($imagen, 201);
    }

    public function show($id)
    {
        $imagen = Imagen::findOrFail($id);
        return response()->json($imagen);
    }

    public function update(Request $request, $id)
    {
        $imagen = Imagen::findOrFail($id);

        $request->validate([
            'idReporte' => 'sometimes|exists:reportes,id',
            'direccion' => 'sometimes|string',
        ]);

        $imagen->update($request->all());

        return response()->json($imagen);
    }

    public function destroy($id)
    {
        $imagen = Imagen::findOrFail($id);
        $imagen->delete();

        return response()->json(['message' => 'Imagen eliminada correctamente']);
    }
}
