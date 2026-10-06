<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHorarioDisponivelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Horas no formato HHMM (ex: 0900, 1730)
        $hora = 'regex:/^([01]\d|2[0-3])[0-5]\d$/';

        return [
            'perito_id' => ['required', 'integer', 'exists:peritos,id'],
            'data' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'hora_inicio' => ['required', 'string', 'size:4', $hora],
            'hora_fim' => ['required', 'string', 'size:4', $hora, function (string $attribute, mixed $value, \Closure $fail) {
                if (is_string($this->hora_inicio) && $value <= $this->hora_inicio) {
                    $fail('A hora final deve ser maior que a hora inicial.');
                }
            }],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
