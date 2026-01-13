<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:30',
        ];
    }

    public function messages()
    {
      return [
        'email.required' => 'É necessário preencher o email',
        'email.max' => 'O email deve ter no máximo 255 caracteres',
        'email.unique' => 'Este email já está cadastrado',
        'password.required' => 'É necessário preencher a senha ',
        'password.min' => 'A senha deve ter no mínimo 6 caracteres',
        'password.max' => 'A senha deve ter no máximo 30 caracteres',
      ];
    }
}
