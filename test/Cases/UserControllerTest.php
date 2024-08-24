<?php

declare(strict_types=1);

namespace Tests\Cases;

use Hyperf\Testing\TestCase;
use App\Model\User;
use Ramsey\Uuid\Uuid;

class UserControllerTest extends TestCase
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
    }

    public function testIndexAll(): void
    {
        $response = $this->get('/api/users/indexAll', [], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(200);
    }

    public function testShow(): void
    {
        $response = $this->get('/api/users/' . $this->user->id, [], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->user->id,
                'name' => 'Test User',
                'email' => $this->user->email,
            ]);
    }

    public function testUpdate(): void
    {
        $response = $this->put('/api/users/' . $this->user->id, [
            'name' => 'Updated User'
        ], [
            'Authorization' => $this->getToken()
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Updated User'
            ]);
    }

    public function testDelete(): void
    {
        $response = $this->delete('/api/users/' . $this->user->id, [], [
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
