<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Model\Expense;
use Hyperf\Collection\Collection;

interface ExpenseRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Expense;

    public function findOrFail(int $id): Expense;

    public function findByCardAndUser(int $userId): ?Expense;

    public function create(array $data): Expense;

    public function update(Expense $expense, array $data): Expense;

    public function delete(Expense $expense): bool;
}
