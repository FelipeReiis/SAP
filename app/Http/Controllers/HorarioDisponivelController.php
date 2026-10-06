<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHorarioDisponivelRequest;
use App\Http\Requests\UpdateHorarioDisponivelRequest;
use App\Http\Resources\HorarioDisponivelResource;
use App\Models\HorarioDisponivel;
use App\Services\HorarioDisponivelService;
use Illuminate\Http\Request;

class HorarioDisponivelController extends Controller
{
    public function index(Request $req)
    {
        // Filtros opcionais: ?perito_id=1&data=2026-10-10&status=1
        $horarios = HorarioDisponivel::with('perito')
            ->when($req->filled('perito_id'), fn ($q) => $q->where('perito_id', $req->integer('perito_id')))
            ->when($req->filled('data'), fn ($q) => $q->whereDate('data', $req->input('data')))
            ->when($req->filled('status'), fn ($q) => $q->where('status', $req->boolean('status')))
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->paginate(15);

        return HorarioDisponivelResource::collection($horarios);
    }

    public function store(StoreHorarioDisponivelRequest $req, HorarioDisponivelService $service)
    {
        $horario = $service->store($req->validated());

        return response()->json([
            'message' => 'Horário cadastrado com sucesso',
            'data'    => new HorarioDisponivelResource($horario),
        ], 201);
    }

    public function update(UpdateHorarioDisponivelRequest $req, HorarioDisponivel $horario, HorarioDisponivelService $service)
    {
        $horarioAtualizado = $service->update($horario, $req->validated());

        return response()->json([
            'message' => 'Horário atualizado com sucesso',
            'data'    => new HorarioDisponivelResource($horarioAtualizado),
        ]);
    }
}
