<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perito extends Model
{
    protected $table = 'peritos';

    protected $fillable = [
        'nome',
        'especialidade',
        'ativo'
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }

    public function horarios(): HasMany
    {
        return $this->hasMany(HorarioDisponivel::class);
    }

    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class);
    }
}
