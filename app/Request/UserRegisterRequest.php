<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class UserRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:60',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'type' => 'required|string|min:5',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser uma string.',
            'name.max' => 'O campo nome não pode ter mais de :max caracteres.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O campo e-mail deve ser um endereço de e-mail válido.',
            'email.unique' => 'Já existe uma conta com este e-mail.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.string' => 'O campo senha deve ser uma string.',
            'password.min' => 'O campo senha deve ter pelo menos :min caracteres.',
            'type.required' => 'O campo tipo é obrigatório.',
            'password.string' => 'O campo tipo tem que ser uma string.',
            'password.min' => 'O campo tipo deve ter pelo menos :min caracteres.',
        ];
    }



}