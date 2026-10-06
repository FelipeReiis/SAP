<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agendamento extends Model
{
    public const STATUS_AGENDADO = 'agendado';
    public const STATUS_CANCELADO = 'cancelado';
    public const STATUS_REALIZADO = 'realizado';

    public const STATUS = [
        self::STATUS_AGENDADO,
        self::STATUS_CANCELADO,
        self::STATUS_REALIZADO,
    ];

    protected $table = 'agendamentos';

    protected $fillable = [
        'status',
        'id_empotency_key',
        'perito_id',
        'servidor_id',
        'disponibilidade_id',
    ];

    public function servidor(): BelongsTo
    {
        return $this->belongsTo(Servidor::class);
    }

    public function perito(): BelongsTo
    {
        return $this->belongsTo(Perito::class);
    }

    public function horario(): BelongsTo
    {
        return $this->belongsTo(HorarioDisponivel::class, 'disponibilidade_id');
    }
}
