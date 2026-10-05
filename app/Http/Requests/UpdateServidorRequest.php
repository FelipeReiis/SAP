<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServidorRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:60'],
            'cpf' => ['required', 'string', 'max:11', Rule::unique('servidors', 'cpf')->ignore($this->route('servidor'))],
            'email' => ['required', 'email', 'max:60', Rule::unique('servidors', 'email')->ignore($this->route('servidor'))],
        ];
    }
}
