<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;    
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller


{
  public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = [
            'correo' => $request->correo,
            'password' => $request->password
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json(['mensaje' => 'Credenciales inválidas'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'usuario' => $user,
            'token' => $token
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:users,correo',
            'password' => 'required|string|min:6',
            'tipo_usuario' => 'required|string'
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => bcrypt($request->password),
            'tipo_usuario' => $request->tipo_usuario
        ]);

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'usuario' => $user,
            'token' => $token
        ]);
    }
}