<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeedbackRequest extends FormRequest
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
            'candidatura_id' => ['required', 'integer', 'exists:candidaturas,id'],
            'etapa_id' => ['nullable', 'integer', 'exists:etapas_candidatura,id'],
            'tipo' => ['required', Rule::in(['empresa', 'pessoal'])],
            'feedback' => ['required', 'string'],
            'nota' => ['nullable', 'integer', 'min:1', 'max:5'],
            'pontos_fortes' => ['nullable', 'string'],
            'pontos_fracos' => ['nullable', 'string'],
            'aprendizados' => ['nullable', 'string'],
            'motivo_rejeicao' => [
                'nullable',
                Rule::in([
                    'perfil_nao_adequado',
                    'conhecimento_tecnico',
                    'experiencia_insuficiente',
                    'pretensao_salarial',
                    'soft_skills',
                    'vaga_cancelada',
                    'outro'
                ])
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'candidatura_id.required' => 'A candidatura é obrigatória.',
            'candidatura_id.exists' => 'A candidatura informada não existe.',
            'etapa_id.exists' => 'A etapa informada não existe.',
            'tipo.required' => 'O tipo de feedback é obrigatório.',
            'tipo.in' => 'O tipo de feedback deve ser "empresa" ou "pessoal".',
            'feedback.required' => 'O feedback é obrigatório.',
            'nota.min' => 'A nota deve ser no mínimo 1.',
            'nota.max' => 'A nota deve ser no máximo 5.',
            'motivo_rejeicao.in' => 'O motivo de rejeição informado é inválido.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'candidatura_id' => 'candidatura',
            'etapa_id' => 'etapa',
            'pontos_fortes' => 'pontos fortes',
            'pontos_fracos' => 'pontos fracos',
            'motivo_rejeicao' => 'motivo de rejeição',
        ];
    }
}