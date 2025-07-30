<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Crea un nuevo usuario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:users,correo',
            'password' => 'required|string|min:6',
            'tipo_usuario' => 'required|in:admin,ciudadano',
        ]);

        $usuario = User::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'tipo_usuario' => $request->tipo_usuario,
        ]);

        // Opcional: puedes ocultar el campo 'password' en la respuesta
        return response()->json([
            'mensaje' => 'Usuario creado con éxito',
            'usuario' => $usuario,
        ], 201);
    }



/**Get de Un Usario  */

public function show($id)
{
    $usuario = User::find($id);

    if (!$usuario) {
        return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
    }

    return response()->json($usuario, 200);
}




/* Eliminar usuario */

    public function destroy($id)
{
    $usuario = User::find($id);

    if (!$usuario) {
        return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
    }

    $usuario->delete();

    return response()->json(['mensaje' => 'Usuario eliminado con éxito'], 200);
}





public function update(Request $request, $id)
{
    $usuario = User::find($id);

    if (!$usuario) {
        return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
    }

    $request->validate([
        'nombre' => 'sometimes|required|string|max:255',
        'correo' => 'sometimes|required|email|unique:users,correo,' . $id,
        'password' => 'sometimes|required|string|min:6',
        'tipo_usuario' => 'sometimes|required|in:admin,ciudadano',
    ]);

    if ($request->has('nombre')) {
        $usuario->nombre = $request->nombre;
    }

    if ($request->has('correo')) {
        $usuario->correo = $request->correo;
    }

    if ($request->has('password')) {
        $usuario->password = Hash::make($request->password);
    }

    if ($request->has('tipo_usuario')) {
        $usuario->tipo_usuario = $request->tipo_usuario;
    }

    $usuario->save();

    return response()->json([
        'mensaje' => 'Usuario actualizado con éxito',
        'usuario' => $usuario,
    ]);
}



}
