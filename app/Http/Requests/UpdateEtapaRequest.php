<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEtapaRequest extends FormRequest
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
            'candidatura_id' => ['sometimes', 'integer', 'exists:candidaturas,id'],
            'tipo_etapa' => [
                'sometimes',
                Rule::in([
                    'triagem',
                    'teste_tecnico',
                    'teste_pratico',
                    'entrevista_rh',
                    'entrevista_tecnica',
                    'entrevista_gestor',
                    'entrevista_final',
                    'proposta',
                    'outro'
                ])
            ],
            'titulo' => ['sometimes', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'status' => [
                'sometimes',
                Rule::in([
                    'agendado',
                    'em_andamento',
                    'concluido',
                    'aprovado',
                    'reprovado',
                    'cancelado'
                ])
            ],
            'data_agendada' => ['nullable', 'date'],
            'data_realizada' => ['nullable', 'date'],
            'data_resposta' => ['nullable', 'date'],
            'feedback_empresa' => ['nullable', 'string'],
            'feedback_pessoal' => ['nullable', 'string'],
            'nota_auto_avaliacao' => ['nullable', 'integer', 'min:1', 'max:5'],
            'entrevistadores' => ['nullable', 'string'],
            'duracao_minutos' => ['nullable', 'integer', 'min:1'],
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
            'candidatura_id.exists' => 'A candidatura informada não existe.',
            'tipo_etapa.in' => 'O tipo da etapa informado é inválido.',
            'titulo.max' => 'O título não pode ter mais de 255 caracteres.',
            'status.in' => 'O status informado é inválido.',
            'data_agendada.date' => 'A data agendada deve ser uma data válida.',
            'data_realizada.date' => 'A data realizada deve ser uma data válida.',
            'data_resposta.date' => 'A data de resposta deve ser uma data válida.',
            'nota_auto_avaliacao.min' => 'A nota de auto avaliação deve ser no mínimo 1.',
            'nota_auto_avaliacao.max' => 'A nota de auto avaliação deve ser no máximo 5.',
            'duracao_minutos.min' => 'A duração deve ser no mínimo 1 minuto.',
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
            'tipo_etapa' => 'tipo da etapa',
            'titulo' => 'título',
            'descricao' => 'descrição',
            'data_agendada' => 'data agendada',
            'data_realizada' => 'data realizada',
            'data_resposta' => 'data de resposta',
            'feedback_empresa' => 'feedback da empresa',
            'feedback_pessoal' => 'feedback pessoal',
            'nota_auto_avaliacao' => 'nota de auto avaliação',
            'duracao_minutos' => 'duração em minutos',
        ];
    }
}