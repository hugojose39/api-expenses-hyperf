<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Card;
use App\Request\CardRequest;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ResponseInterface;

class CardController extends AbstractController
{
    public function index(): ResponseInterface
    {
        $cards = Card::all();
        return $this->response->json($cards);
    }

    public function store(CardRequest $request): ResponseInterface
    {
        $card = Card::create($request->validated());
        return $this->response->json($card);
    }

    public function show(int $id): ResponseInterface
    {
        $card = Card::findOrFail($id);
        return $this->response->json($card);
    }

    public function update(CardRequest $request, int $id): ResponseInterface
    {
        $card = Card::findOrFail($id);
        $card->update($request->validated());
        return $this->response->json($card);
    }

    public function delete(int $id): ResponseInterface
    {
        $card = Card::findOrFail($id);
        $card->delete();
        return $this->response->json(['message' => 'Cartão deletado com sucesso']);
    }
}
