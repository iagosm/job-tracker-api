<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlataformaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => [
                'required',
                'string',
                'max:100',
                // ignora o próprio ID no update
                'unique:plataformas,nome,'.$this->route('id'),
            ],
            'url' => [
                'nullable',
                'string',
                'max:255',
                'url',
            ],
            'icone' => [
                'nullable',
                'string',
                'max:255',
                'url',
            ],
            'ativa' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome da plataforma é obrigatório.',
            'nome.unique' => 'Essa plataforma já está cadastrada.',
            'url.url' => 'A URL informada é inválida.',
            'icone.url' => 'O ícone deve ser uma URL válida.',
            'ativa.boolean' => 'O campo ativa deve ser true ou false.',
        ];
    }
}
