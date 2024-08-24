<?php

declare(strict_types=1);

namespace Tests\Cases;

use Hyperf\Testing\TestCase;
use App\Model\User;
use Ramsey\Uuid\Uuid;

class AuthControllerTest extends TestCase
{
    public function testRegister(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => $uuid.'johndoe@example.com',
            'password' => 'Password1234',
            'type' => 'admin'
        ]);

        $response->assertStatus(201);
    }

    public function testLogin(): void
    {
        $uuid = Uuid::uuid4()->toString();

        User::create([
            'name' => 'Jane Doe',
            'uuid' => $uuid,
            'email' => $uuid.'janedoe@example.com',
            'password' => password_hash('Password1234', PASSWORD_BCRYPT),
            'type' => 'admin'
        ]);

        $response = $this->post('/api/login', [
            'email' => $uuid.'janedoe@example.com',
            'password' => 'Password1234'
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token']);
    }
}
