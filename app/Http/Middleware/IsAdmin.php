<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->tipo_usuario !== 'admin') {
            return response()->json(['mensaje' => 'Acceso denegado. Solo para administradores.'], 403);
        }

        return $next($request);
    }
}
