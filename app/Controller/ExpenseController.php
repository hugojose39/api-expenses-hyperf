<?php

declare(strict_types=1);

namespace App\Controller;

use App\Event\ExpenseCreated;
use App\Model\Card;
use App\Model\Expense;
use App\Model\User;
use App\Request\ExpenseRequest;
use Hyperf\Coroutine\Parallel;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class ExpenseController extends AbstractController
{
    public function __construct(
        private readonly Card $card,
        private readonly Expense $expense,
        private readonly User $user,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function indexAll(): PsrResponseInterface
    {
        $expenses = $this->expense->all();
        return $this->response->json($expenses);
    }

    public function index(): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $expenses = $this->expense->whereHas('card', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return $this->response->json($expenses);
    }

    public function show(int $id): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $expense = $this->expense->where('id', $id)
            ->whereHas('card', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->first();

        if (!$expense) {
            return $this->response->json(['message' => 'Despesa não encontrada ou não pertence ao usuário'])->withStatus(403);
        }

        return $this->response->json($expense);
    }

    public function store(ExpenseRequest $request): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $input = $request->validated();

        $card = $this->card->findOrFail($input['card_id']);

        if (!$card->hasSufficientBalance((float)$input['amount'])) {
            return $this->response->json(['message' => 'Saldo insuficiente'])->withStatus(422);
        }

        $parallel = new Parallel();

        $parallel->add(
            function () use ($input) {
                $this->expense->create($input);
            },
            $user->id
        );

        if (!empty($parallel->wait())) {
            $card->balance -= (float)$input['amount'];
            $card->save();

            $users = $this->user->where(function ($query) use ($user) {
                $query->where('id', $user->id)
                    ->orWhere('type', 'admin');
            })->get();

            $this->eventDispatcher->dispatch(new ExpenseCreated($users));

            return $this->response->json(['message' => 'Despesa criada com sucesso'])->withStatus(201);
        }

        return $this->response->json(['message' => 'Não foi possível criar sua despesa'])->withStatus(422);
    }

    public function update(ExpenseRequest $request, int $id): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $input = $request->validated();

        $card = $this->card->findOrFail($input['card_id']);
        $expense = $this->expense->findOrFail($id);

        if (!$card->hasSufficientBalance((float)$input['amount'])) {
            return $this->response->json(['message' => 'Saldo insuficiente'])->withStatus(422);
        }

        $parallel = new Parallel();

        $parallel->add(
            function () use ($input, $expense) {
                $expense->update($input);
            },
            $user->id
        );

        if (!empty($parallel->wait())) {
            $card->balance -= (float)$input['amount'];
            $card->save();

            return $this->response->json($expense);
        }

        return $this->response->json(['message' => 'Não foi possível atualizar sua despesa'])->withStatus(422);
    }

    public function delete(int $id): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $expense = $this->expense->where('id', $id)
            ->whereHas('card', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->first();

        if (!$expense) {
            return $this->response->json(['message' => 'Despesa não encontrada ou não pertence ao usuário'])->withStatus(403);
        }

        $expense->delete();
        return $this->response->json(['message' => 'Despesa apagada com sucesso']);
    }
}
