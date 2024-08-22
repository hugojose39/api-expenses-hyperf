<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class CardRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'number' => 'required|string|unique:cards,number',
            'balance' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'O campo ID do usuário é obrigatório.',
            'user_id.exists' => 'O ID do usuário informado não existe.',
            'number.required' => 'O campo número do cartão é obrigatório.',
            'number.string' => 'O campo número do cartão deve ser uma string.',
            'number.unique' => 'O número do cartão informado já está em uso.',
            'balance.required' => 'O campo saldo é obrigatório.',
            'balance.numeric' => 'O campo saldo deve ser um número.',
            'balance.min' => 'O campo saldo deve ser maior ou igual a 0.',
        ];
    }
}
