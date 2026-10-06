<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servidor extends Model
{
    protected $table = 'servidors';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'cpf',
        'email'
    ];

    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class);
    }
}
