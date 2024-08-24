<?php

declare(strict_types=1);

namespace Tests\Cases;

use App\Model\Card;
use Hyperf\Testing\TestCase;
use App\Model\User;
use Ramsey\Uuid\Uuid;

class CardControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $uuid = Uuid::uuid4()->toString();

        $this->user = User::create([
            'uuid' => $uuid,
            'name' => 'Test User',
            'email' => $uuid.'testuser@example.com',
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
        $response = $this->get('/api/cards', [], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(200);
    }

    public function testIndex(): void
    {
        $response = $this->get('/api/cards', [], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(200);
    }

    public function testShow(): void
    {
        $response = $this->get('/api/cards/' . $this->card->id, [], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->card->id,
                'number' => $this->card->number,
            ]);
    }

    public function testStore(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $response = $this->post('/api/cards', [
            'user_id' => $this->user->id,
            'number' => $uuid,
            'balance' => 500
        ], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'number' => $uuid
            ]);
    }

    public function testUpdate(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $response = $this->put('/api/cards/' . $this->card->id, [
            'user_id' => $this->user->id,
            'number' => $uuid,
            'balance' => 1500
        ], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'balance' => 1500
            ]);
    }

    public function testDelete(): void
    {
        $response = $this->delete('/api/cards/' . $this->card->id, [], [
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
