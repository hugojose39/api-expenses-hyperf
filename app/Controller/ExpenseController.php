<?php

declare(strict_types=1);

namespace App\Controller;

use App\Event\ExpenseCreated;
use App\Interfaces\ExpenseRepositoryInterface;
use App\Model\Card;
use App\Model\User;
use App\Request\ExpenseRequest;
use Hyperf\Coroutine\Parallel;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class ExpenseController extends AbstractController
{
    public function __construct(
        private readonly ExpenseRepositoryInterface $expenseRepository,
        private readonly Card $card,
        private readonly User $user,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function indexAll(): PsrResponseInterface
    {
        $expenses = $this->expenseRepository->all();
        return $this->response->json($expenses);
    }

    public function index(): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $expenses = $this->expenseRepository->findByCardAndUser($user->id);
        return $this->response->json($expenses);
    }

    public function show(int $id): PsrResponseInterface
    {
        $expense = $this->expenseRepository->find($id);

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
            fn () => $this->expenseRepository->create($input),
            $user->id
        );

        if (!empty($parallel->wait())) {
            $card->balance -= (float)$input['amount'];
            $card->save();

            $users = $this->user->where(fn ($query) => $query->where('id', $user->id)
                ->orWhere('type', 'admin'))->get();

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
        $expense = $this->expenseRepository->findOrFail($id);

        if (!$card->hasSufficientBalance((float)$input['amount'])) {
            return $this->response->json(['message' => 'Saldo insuficiente'])->withStatus(422);
        }

        $parallel = new Parallel();

        $parallel->add(
            fn () => $this->expenseRepository->update($expense, $input),
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
        $expense = $this->expenseRepository->find($id);

        if (!$expense) {
            return $this->response->json(['message' => 'Despesa não encontrada ou não pertence ao usuário'])->withStatus(403);
        }

        $this->expenseRepository->delete($expense);
        return $this->response->json(['message' => 'Despesa apagada com sucesso']);
    }
}
