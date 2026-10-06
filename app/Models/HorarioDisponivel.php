<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HorarioDisponivel extends Model
{
    protected $table = 'horario_disponivels';

    protected $fillable = [
        'perito_id',
        'data',
        'hora_inicio',
        'hora_fim',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'date:Y-m-d',
            'status' => 'boolean',
        ];
    }

    public function perito(): BelongsTo
    {
        return $this->belongsTo(Perito::class);
    }

    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class, 'disponibilidade_id');
    }
}
