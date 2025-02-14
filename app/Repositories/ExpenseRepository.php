<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ExpenseRepositoryInterface;
use App\Model\Expense;
use Hyperf\Collection\Collection;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    public function all(): Collection
    {
        return Expense::all();
    }

    public function find(int $id): ?Expense
    {
        return Expense::find($id);
    }

    public function findOrFail(int $id): Expense
    {
        return Expense::findOrFail($id);
    }

    public function findByCardAndUser(int $userId): ?Expense
    {
        return Expense::query()
            ->whereHas('card', fn ($query) => $query->where('user_id', $userId))
            ->first();
    }

    public function create(array $data): Expense
    {
        return Expense::create($data);
    }

    public function update(Expense $expense, array $data): Expense
    {
        $expense->update($data);
        return $expense;
    }

    public function delete(Expense $expense): bool
    {
        return $expense->delete();
    }
}
