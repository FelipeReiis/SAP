<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgendamentoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'id_empotency_key' => $this->id_empotency_key,
            'servidor' => new ServidorResource($this->whenLoaded('servidor')),
            'perito' => new PeritoResource($this->whenLoaded('perito')),
            'horario' => new HorarioDisponivelResource($this->whenLoaded('horario')),
            'criado_em' => $this->created_at,
        ];
    }
}
