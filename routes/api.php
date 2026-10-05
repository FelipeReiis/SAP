<?php

use App\Http\Controllers\ServidorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/servidor/cadastrar',[ServidorController::class, 'store']);
Route::get('/servidores/listar',[ServidorController::class, 'index']);
Route::put('/servidores/atualizar/{servidor}',[ServidorController::class, 'update']);
