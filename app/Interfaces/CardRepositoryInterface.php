<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Model\Card;
use Hyperf\Collection\Collection;

interface CardRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Card;

    public function findOrFail(int $id): Card;

    public function findByUserId(int $userId): Collection;

    public function create(array $data): Card;

    public function update(Card $card, array $data): Card;

    public function delete(Card $card): bool;
}
