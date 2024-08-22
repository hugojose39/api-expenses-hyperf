<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Card;
use App\Request\CardRequest;
use Psr\Http\Message\ResponseInterface;

class CardController extends AbstractController
{
    public function __construct(
        private readonly Card $card)
    {
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

        return $this->response->json(['message' => 'Acesso negado'], 403);
    }

    public function store(CardRequest $request): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $input = $request->validated();

        // Apenas o usuário autenticado pode criar cartões para si mesmo
        $input['user_id'] = $user->id;
        $card = $this->card->create($input);

        return $this->response->json($card);
    }

    public function update(CardRequest $request, int $id): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $card = $this->card->findOrFail($id);

        // Administradores podem atualizar qualquer cartão
        // Usuários comuns só podem atualizar seus próprios cartões
        if ($user->type === 'admin' || $card->user_id === $user->id) {
            $card->update($request->validated());
            return $this->response->json($card);
        }

        return $this->response->json(['message' => 'Acesso negado'], 403);
    }

    public function delete(int $id): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();
        $card = $this->card->findOrFail($id);

        // Administradores podem deletar qualquer cartão
        // Usuários comuns só podem deletar seus próprios cartões
        if ($user->type === 'admin' || $card->user_id === $user->id) {
            $card->delete();
            return $this->response->json(['message' => 'Cartão deletado com sucesso']);
        }

        return $this->response->json(['message' => 'Acesso negado'], 403);
    }
}
