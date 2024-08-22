<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Card;
use App\Model\Expense;
use App\Request\ExpenseRequest;
use Hyperf\DbConnection\Db;
use Psr\Http\Message\ResponseInterface;

class ExpenseController extends AbstractController
{
    public function store(ExpenseRequest $request): ResponseInterface
    {
        $user = $this->container->get('user'); // Obtém o usuário autenticado

        $card = Card::findOrFail($request->card_id);

        if (!$card->hasSufficientBalance($request->amount)) {
            return $this->response->json(['message' => 'Saldo insuficiente'], 422);
        }

        Db::transaction(function () use ($card, $request, $user) {
            $card->balance -= $request->amount;
            $card->save();

            $expense = Expense::create(array_merge($request->validated(), ['user_id' => $user->id]));
            
            // Aqui dispararia o evento de envio de email para o usuário e administradores.
        });

        return $this->response->json(['message' => 'Despesa criada com sucesso']);
    }

    public function index(): ResponseInterface
    {
        $expenses = Expense::all();
        return $this->response->json($expenses);
    }

    public function show(int $id): ResponseInterface
    {
        $expense = Expense::findOrFail($id);
        return $this->response->json($expense);
    }

    public function delete(int $id): ResponseInterface
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        return $this->response->json(['message' => 'Despesa apagada com sucesso']);
    }
}
