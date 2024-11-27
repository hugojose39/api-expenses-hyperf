<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Model\User;
use Hyperf\Collection\Collection;

interface UserRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?User;

    public function findOrFail(int $id): User;

    public function update(User $user, array $data): User;

    public function delete(User $user): bool;
}
