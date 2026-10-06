<?php

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\HorarioDisponivelController;
use App\Http\Controllers\PeritoController;
use App\Http\Controllers\ServidorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/servidor/cadastrar',[ServidorController::class, 'store']);
Route::get('/servidores/listar',[ServidorController::class, 'index']);
Route::put('/servidores/atualizar/{servidor}',[ServidorController::class, 'update']);

Route::post('/perito/cadastrar',[PeritoController::class, 'store']);
Route::get('/peritos/listar',[PeritoController::class, 'index']);
Route::put('/perito/atualizar/{perito}',[PeritoController::class, 'update']);

Route::post('/horario/cadastrar',[HorarioDisponivelController::class, 'store']);
Route::get('/horarios/listar',[HorarioDisponivelController::class, 'index']);
Route::put('/horario/atualizar/{horario}',[HorarioDisponivelController::class, 'update']);

Route::post('/agendamento/cadastrar',[AgendamentoController::class, 'store']);
Route::get('/agendamentos/listar',[AgendamentoController::class, 'index']);
Route::put('/agendamento/atualizar/{agendamento}',[AgendamentoController::class, 'update']);
