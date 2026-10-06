<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HorarioDisponivelResource extends JsonResource
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
            'perito_id' => $this->perito_id,
            'perito' => new PeritoResource($this->whenLoaded('perito')),
            'data' => $this->data?->format('Y-m-d'),
            'hora_inicio' => $this->hora_inicio,
            'hora_fim' => $this->hora_fim,
            'status' => $this->status,
        ];
    }
}
