<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\CardRepositoryInterface;
use App\Model\Card;
use Hyperf\Collection\Collection;

class CardRepository implements CardRepositoryInterface
{
    public function all(): Collection
    {
        return Card::all();
    }

    public function find(int $id): ?Card
    {
        return Card::find($id);
    }

    public function findOrFail(int $id): Card
    {
        return Card::findOrFail($id);
    }

    public function findByUserId(int $userId): Collection
    {
        return Card::where('user_id', $userId)->get();
    }

    public function create(array $data): Card
    {
        return Card::create($data);
    }

    public function update(Card $card, array $data): Card
    {
        $card->update($data);
        return $card;
    }

    public function delete(Card $card): bool
    {
        return $card->delete();
    }
}
