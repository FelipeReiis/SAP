<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgendamentoRequest;
use App\Http\Requests\UpdateAgendamentoRequest;
use App\Http\Resources\AgendamentoResource;
use App\Models\Agendamento;
use App\Services\AgendamentoService;
use Illuminate\Http\Request;

class AgendamentoController extends Controller
{
    public function index(Request $req)
    {
        // Filtros opcionais: ?servidor_id=1&perito_id=1&status=agendado
        $agendamentos = Agendamento::with(['servidor', 'perito', 'horario'])
            ->when($req->filled('servidor_id'), fn ($q) => $q->where('servidor_id', $req->integer('servidor_id')))
            ->when($req->filled('perito_id'), fn ($q) => $q->where('perito_id', $req->integer('perito_id')))
            ->when($req->filled('status'), fn ($q) => $q->where('status', $req->input('status')))
            ->latest()
            ->paginate(15);

        return AgendamentoResource::collection($agendamentos);
    }

    public function store(StoreAgendamentoRequest $req, AgendamentoService $service)
    {
        $agendamento = $service->store($req->validated());

        // 201 quando criado agora; 200 quando é reenvio com a mesma chave de idempotência
        return response()->json([
            'message' => 'Agendamento realizado com sucesso',
            'data'    => new AgendamentoResource($agendamento->load(['servidor', 'perito', 'horario'])),
        ], $agendamento->wasRecentlyCreated ? 201 : 200);
    }

    public function update(UpdateAgendamentoRequest $req, Agendamento $agendamento, AgendamentoService $service)
    {
        $agendamentoAtualizado = $service->update($agendamento, $req->validated());

        return response()->json([
            'message' => 'Agendamento atualizado com sucesso',
            'data'    => new AgendamentoResource($agendamentoAtualizado),
        ]);
    }
}
