<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TecnologiaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:100', 'unique:tecnologias,nome,'.$this->route('id')],
            'categoria' => ['nullable', 'string', 'in:backend,frontend,database,devops'],
            'icone' => ['nullable', 'string', 'max:255', 'url'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome da tecnologia é obrigatório.',
            'nome.unique' => 'Essa tecnologia já existe.',
            'categoria.in' => 'Categoria inválida. Deve ser backend, frontend, database ou devops.',
            'icone.url' => 'O campo ícone deve ser uma URL válida.',
        ];
    }
}
