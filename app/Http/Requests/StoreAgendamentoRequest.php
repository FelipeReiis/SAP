<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgendamentoRequest extends FormRequest
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
        return [
            'servidor_id' => ['required', 'integer', 'exists:servidors,id'],
            'disponibilidade_id' => ['required', 'integer', 'exists:horario_disponivels,id'],
            // Chave gerada pelo cliente para evitar agendamento duplicado em reenvios
            'id_empotency_key' => ['required', 'string', 'max:100'],
        ];
    }
}
