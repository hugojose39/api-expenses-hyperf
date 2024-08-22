<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'card_id' => 'required|exists:cards,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'card_id.required' => 'O campo ID do cartão é obrigatório.',
            'card_id.exists' => 'O ID do cartão informado não existe.',
            'amount.required' => 'O campo valor é obrigatório.',
            'amount.numeric' => 'O campo valor deve ser um número.',
            'amount.min' => 'O campo valor deve ser maior ou igual a 0.',
            'description.string' => 'O campo descrição deve ser uma string.',
        ];
    }
}
