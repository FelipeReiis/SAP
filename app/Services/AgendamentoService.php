<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\HorarioDisponivel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AgendamentoService
{
    public function store(array $dados): Agendamento
    {
        // Reenvio com a mesma chave devolve o agendamento já criado
        $existente = Agendamento::where('id_empotency_key', $dados['id_empotency_key'])->first();
        if ($existente) {
            return $existente;
        }

        return DB::transaction(function () use ($dados) {
            // Trava a linha do horário para evitar dois agendamentos simultâneos no mesmo slot
            $horario = HorarioDisponivel::with('perito')
                ->lockForUpdate()
                ->findOrFail($dados['disponibilidade_id']);

            if (! $horario->status) {
                throw ValidationException::withMessages([
                    'disponibilidade_id' => 'Este horário não está disponível.',
                ]);
            }

            if (! $horario->perito?->ativo) {
                throw ValidationException::withMessages([
                    'disponibilidade_id' => 'O perito deste horário não está ativo.',
                ]);
            }

            $agendamento = Agendamento::create([
                'servidor_id' => $dados['servidor_id'],
                'perito_id' => $horario->perito_id,
                'disponibilidade_id' => $horario->id,
                'id_empotency_key' => $dados['id_empotency_key'],
                'status' => Agendamento::STATUS_AGENDADO,
            ]);

            $horario->update(['status' => false]);

            return $agendamento;
        });
    }

    public function show($id)
    {
        return Agendamento::with(['servidor', 'perito', 'horario'])->findOrFail($id);
    }

    public function update(Agendamento $agendamento, array $dados): Agendamento
    {
        if ($agendamento->status !== Agendamento::STATUS_AGENDADO) {
            throw ValidationException::withMessages([
                'status' => "Agendamento com status '{$agendamento->status}' não pode ser alterado.",
            ]);
        }

        DB::transaction(function () use ($agendamento, $dados) {
            $agendamento->update(['status' => $dados['status']]);

            // Cancelamento libera o horário para um novo agendamento
            if ($dados['status'] === Agendamento::STATUS_CANCELADO) {
                $agendamento->horario()->update(['status' => true]);
            }
        });

        return $agendamento->refresh()->load(['servidor', 'perito', 'horario']);
    }
}
