<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EstadoController;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/prueba', function () {
    return response()->json(['mensaje' => 'Todo funciona correctamente']);
});

// Rutas protegidas con Sanctum 
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/perfil', function (Request $request) {
        return $request->user();
    });


    // RUTAS DE REPORTES
    Route::post('/reportes', [ReporteController::class, 'store']);
    Route::get('/reportes', [ReporteController::class, 'index']);
    Route::put('reportes/{id}/estado', [ReporteController::class, 'actualizarEstado']); // opcional: para admins
    Route::delete('reportes/{id}', [ReporteController::class, 'destroy']); // opcional: para admins
});
