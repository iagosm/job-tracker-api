<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidaturaFilterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'tipo_trabalho' => 'nullable|in:remoto,presencial,hibrido',
            'empresa' => 'nullable|string|max:255',
            'nivel_vaga' => 'nullable|string',
            'plataforma_id' => 'nullable|integer|exists:plataformas,id',
            'salario_min' => 'nullable|numeric|min:0',
            'salario_max' => 'nullable|numeric|min:0',
            'status' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:50',
        ];
    }
}
