<?php

declare(strict_types=1);

namespace Tests\Cases;

use Hyperf\Testing\TestCase;
use App\Model\Expense;
use App\Model\Card;
use App\Model\User;
use Ramsey\Uuid\Uuid;

class ExpenseControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $uuid = Uuid::uuid4()->toString();

        $this->user = User::create([
            'uuid' => $uuid,
            'name' => 'Test User',
            'email' => $uuid . 'testuser@example.com',
            'password' => password_hash('password', PASSWORD_BCRYPT),
            'type' => 'admin'
        ]);

        $this->card = Card::create([
            'user_id' => $this->user->id,
            'number' => $uuid,
            'balance' => 1000
        ]);
    }

    public function testIndexAll(): void
    {
        Expense::create([
            'card_id' => $this->card->id,
            'amount' => 300,
            'description' => 'Expense to list',
        ]);

        $response = $this->get('/api/expenses', [], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(200);
    }

    public function testIndex(): void
    {
        Expense::create([
            'card_id' => $this->card->id,
            'amount' => 300,
            'description' => 'Expense to list',
        ]);

        $response = $this->get('/api/expenses', [], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(200);
    }

    public function testShow(): void
    {
        $expense = Expense::create([
            'card_id' => $this->card->id,
            'amount' => 150,
            'description' => 'Expense to show',
        ]);

        $response = $this->get("/api/expenses/{$expense->id}", [], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'amount' => 150,
                'description' => 'Expense to show',
            ]);
    }

    public function testUpdate(): void
    {
        $expense = Expense::create([
            'card_id' => $this->card->id,
            'amount' => 400,
            'description' => 'Old description',
        ]);

        $response = $this->put("/api/expenses/{$expense->id}", [
            'card_id' => $this->card->id,
            'amount' => 500,
            'description' => 'Updated description',
        ], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'amount' => 500,
                'description' => 'Updated description',
            ]);

    }

    public function testDelete(): void
    {
        $expense = Expense::create([
            'card_id' => $this->card->id,
            'amount' => 250,
            'description' => 'Expense to delete',
        ]);

        $response = $this->delete("/api/expenses/{$expense->id}", [], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(200);
    }

    private function getToken(): string
    {
        $response = $this->post('/api/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        return $response['token'];
    }
}
