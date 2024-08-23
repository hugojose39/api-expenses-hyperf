<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Card;
use App\Request\CardRequest;
use Psr\Http\Message\ResponseInterface;

class CardController extends AbstractController
{
    public function __construct(
        private readonly Card $card
    ) {
    }

    public function indexAll(): ResponseInterface
    {
        $cards = $this->card->all();

        return $this->response->json($cards);
    }

    public function index(): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $cards = $this->card->where('user_id', $user->id)->get();

        return $this->response->json($cards);
    }

    public function show(int $id): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $card = $this->card->findOrFail($id);

        if ($card->user_id === $user->id) {
            return $this->response->json($card);
        }

        return $this->response->json(['message' => 'Acesso negado'])->withStatus(403);
    }

    public function store(CardRequest $request): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $input = $request->validated();
        $input['user_id'] = $user->id;
        $card = $this->card->create($input);

        return $this->response->json($card)->withStatus(201);
    }

    public function update(CardRequest $request, int $id): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $card = $this->card->findOrFail($id);

        if ($user->type === 'admin' || $card->user_id === $user->id) {
            $card->update($request->validated());
            return $this->response->json($card);
        }

        return $this->response->json(['message' => 'Acesso negado'])->withStatus(403);
    }

    public function delete(int $id): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $card = $this->card->findOrFail($id);

        if ($user->type === 'admin' || $card->user_id === $user->id) {
            $card->delete();
            return $this->response->json(['message' => 'Cartão deletado com sucesso']);
        }

        return $this->response->json(['message' => 'Acesso negado'])->withStatus(403);
    }
}
