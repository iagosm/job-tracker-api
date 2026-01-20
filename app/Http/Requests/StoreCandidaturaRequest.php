<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCandidaturaRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'plataforma_id' => [
        'required',
        'integer',
        'exists:plataformas,id',
      ],

      'cargo' => [
        'required',
        'string',
        'max:255',
      ],

      'empresa' => [
        'required',
        'string',
        'max:255',
      ],

      'link_vaga' => [
        'nullable',
        'url',
        'max:500',
      ],

      'tipo_trabalho' => [
        'required',
        Rule::in(['remoto', 'presencial', 'hibrido']),
      ],

      'localizacao' => [
        'nullable',
        'string',
        'max:255',
      ],

      'nivel_vaga' => [
        'required',
        Rule::in(['estagio', 'junior', 'pleno', 'senior', 'especialista']),
      ],

      'salario_minimo' => [
        'nullable',
        'numeric',
        'min:0',
        'lte:salario_maximo',
      ],

      'salario_maximo' => [
        'nullable',
        'numeric',
        'min:0',
        'gte:salario_minimo',
      ],

      'salario_a_combinar' => [
        'required',
        'boolean',
      ],

      'curriculo_visualizado' => [
        'required',
        'boolean',
      ],

      'status' => [
        'required',
        Rule::in([
          'aplicado',
          'em_analise',
          'triagem',
          'teste_tecnico',
          'entrevista_rh',
          'entrevista_tecnica',
          'entrevista_final',
          'proposta',
          'contratado',
          'rejeitado',
          'desistiu',
        ]),
      ],

      'data_aplicacao' => [
        'required',
        'date',
        'before_or_equal:today',
      ],

      'observacoes' => [
        'nullable',
        'string',
      ],

      'requisitos' => [
        'nullable',
        'string',
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'plataforma_id.required' => 'A plataforma é obrigatória.',
      'plataforma_id.integer' => 'A plataforma informada é inválida.',
      'plataforma_id.exists' => 'A plataforma selecionada não existe.',

      'cargo.required' => 'O cargo é obrigatório.',
      'cargo.string' => 'O cargo deve ser um texto válido.',
      'cargo.max' => 'O cargo pode ter no máximo 255 caracteres.',

      'empresa.required' => 'A empresa é obrigatória.',
      'empresa.string' => 'O nome da empresa deve ser um texto válido.',
      'empresa.max' => 'O nome da empresa pode ter no máximo 255 caracteres.',

      'link_vaga.url' => 'O link da vaga deve ser uma URL válida.',
      'link_vaga.max' => 'O link da vaga é muito longo.',

      'tipo_trabalho.required' => 'O tipo de trabalho é obrigatório.',
      'tipo_trabalho.in' => 'O tipo de trabalho deve ser remoto, presencial ou híbrido.',

      'localizacao.string' => 'A localização deve ser um texto válido.',
      'localizacao.max' => 'A localização pode ter no máximo 255 caracteres.',

      'nivel_vaga.required' => 'O nível da vaga é obrigatório.',
      'nivel_vaga.in' => 'O nível da vaga informado é inválido.',

      'salario_minimo.numeric' => 'O salário mínimo deve ser um valor numérico.',
      'salario_minimo.min' => 'O salário mínimo não pode ser negativo.',
      'salario_minimo.lte' => 'O salário mínimo não pode ser maior que o salário máximo.',

      'salario_maximo.numeric' => 'O salário máximo deve ser um valor numérico.',
      'salario_maximo.min' => 'O salário máximo não pode ser negativo.',
      'salario_maximo.gte' => 'O salário máximo não pode ser menor que o salário mínimo.',

      'salario_a_combinar.required' => 'Informe se o salário é a combinar.',
      'salario_a_combinar.boolean' => 'O campo salário a combinar deve ser verdadeiro ou falso.',

      'curriculo_visualizado.required' => 'Informe se o currículo foi visualizado.',
      'curriculo_visualizado.boolean' => 'O campo currículo visualizado deve ser verdadeiro ou falso.',

      'status.required' => 'O status da candidatura é obrigatório.',
      'status.in' => 'O status informado é inválido.',

      'data_aplicacao.required' => 'A data da aplicação é obrigatória.',
      'data_aplicacao.date' => 'A data da aplicação deve ser uma data válida.',
      'data_aplicacao.before_or_equal' => 'A data da aplicação não pode ser futura.',

      'observacoes.string' => 'As observações devem ser um texto válido.',
      'requisitos.string' => 'Os requisitos devem ser um texto válido.',
    ];
  }

  public function withValidator($validator): void
  {
    $validator->after(function ($validator) {
      if (
        $this->boolean('salario_a_combinar') &&
        ($this->salario_minimo || $this->salario_maximo)
      ) {
        $validator->errors()->add(
          'salario_a_combinar',
          'Quando o salário for "a combinar", não informe salário mínimo ou máximo.'
        );
      }
    });
  }
}
