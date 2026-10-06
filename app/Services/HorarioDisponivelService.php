<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\HorarioDisponivel;
use Illuminate\Validation\ValidationException;

class HorarioDisponivelService
{
    public function store(array $horario): HorarioDisponivel
    {
        $this->validarConflito($horario);

        return HorarioDisponivel::create($horario + ['status' => true]);
    }

    public function show($id)
    {
        return HorarioDisponivel::with('perito')->findOrFail($id);
    }

    public function update(HorarioDisponivel $horario, array $dados): HorarioDisponivel
    {
        $temAgendamentoAtivo = $horario->agendamentos()
            ->where('status', Agendamento::STATUS_AGENDADO)
            ->exists();

        if ($temAgendamentoAtivo) {
            throw ValidationException::withMessages([
                'horario' => 'Não é possível alterar um horário com agendamento ativo.',
            ]);
        }

        $this->validarConflito($dados, $horario->id);

        $horario->update($dados);

        return $horario->refresh();
    }

    /**
     * Impede que o mesmo perito tenha horários sobrepostos no mesmo dia.
     */
    private function validarConflito(array $dados, ?int $ignorarId = null): void
    {
        $conflito = HorarioDisponivel::where('perito_id', $dados['perito_id'])
            ->whereDate('data', $dados['data'])
            ->where('hora_inicio', '<', $dados['hora_fim'])
            ->where('hora_fim', '>', $dados['hora_inicio'])
            ->when($ignorarId, fn ($q) => $q->where('id', '!=', $ignorarId))
            ->exists();

        if ($conflito) {
            throw ValidationException::withMessages([
                'hora_inicio' => 'O perito já possui um horário cadastrado que conflita com este intervalo.',
            ]);
        }
    }
}
