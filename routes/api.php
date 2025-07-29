<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EstadoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {

    // Usuarios (solo admin)
    Route::middleware('admin')->group(function () {
        Route::post('/usuarios', [UserController::class, 'store']);
        Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);
        Route::put('/usuarios/{id}', [UserController::class, 'update']);
    });

    Route::get('/usuarios/{id}', [UserController::class, 'show']);

    // Categorías (admin)
    Route::middleware('admin')->group(function () {
        Route::post('/categorias', [CategoriaController::class, 'store']);
        Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
        Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);
    });

    Route::get('/categorias/{id}', [CategoriaController::class, 'show']);

    // Estados (admin)
    Route::middleware('admin')->group(function () {
        Route::post('/estados', [EstadoController::class, 'store']);
        Route::put('/estados/{id}', [EstadoController::class, 'update']);
        Route::delete('/estados/{id}', [EstadoController::class, 'destroy']);
    });

    Route::get('/estados/{id}', [EstadoController::class, 'show']);

    // Ruta para ver el usuario logueado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});



Route::post('/estados', [EstadoController::class, 'store']);
Route::get('/estados/{id}', [EstadoController::class, 'show']);
Route::put('/estados/{id}', [EstadoController::class, 'update']);
Route::delete('/estados/{id}', [EstadoController::class, 'destroy']);
