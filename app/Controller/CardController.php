<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interfaces\CardRepositoryInterface;
use App\Request\CardRequest;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class CardController extends AbstractController
{
    public function __construct(
        private readonly CardRepositoryInterface $cardRepository
    ) {
    }

    public function indexAll(): PsrResponseInterface
    {
        $cards = $this->cardRepository->all();
        return $this->response->json($cards);
    }

    public function index(): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $cards = $this->cardRepository->findByUserId($user->id);
        return $this->response->json($cards);
    }

    public function show(int $id): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $card = $this->cardRepository->findOrFail($id);

        if ($card->user_id === $user->id) {
            return $this->response->json($card);
        }

        return $this->response->json(['message' => 'Acesso negado'])->withStatus(403);
    }

    public function store(CardRequest $request): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $input = $request->validated();
        $input['user_id'] = $user->id;

        $card = $this->cardRepository->create($input);
        return $this->response->json($card)->withStatus(201);
    }

    public function update(CardRequest $request, int $id): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $card = $this->cardRepository->findOrFail($id);

        if ($user->type === 'admin' || $card->user_id === $user->id) {
            $updatedCard = $this->cardRepository->update($card, $request->validated());
            return $this->response->json($updatedCard);
        }

        return $this->response->json(['message' => 'Acesso negado'])->withStatus(403);
    }

    public function delete(int $id): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $card = $this->cardRepository->findOrFail($id);

        if ($user->type === 'admin' || $card->user_id === $user->id) {
            $this->cardRepository->delete($card);
            return $this->response->json(['message' => 'Cartão deletado com sucesso']);
        }

        return $this->response->json(['message' => 'Acesso negado'])->withStatus(403);
    }
}
