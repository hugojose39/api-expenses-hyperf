<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\LoginRepositoryInterface;
use App\Model\User;
use App\Request\LoginRequest;
use App\Request\UserRegisterRequest;
use App\Services\JWTService;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class LoginRepository implements LoginRepositoryInterface
{
    public function login(LoginRequest $request): array
    {
        $input = $request->validated();

        $email = $input['email'];
        $password = $input['password'];

        $user = $this->getUserByEmail($email);

        if (!$user) {
            return ['error' => 'Usuário não encontrado'];
        }

        if (password_verify($password, $user->password)) {
            $tokenPayload = [
                'uuid' => $user->uuid,
                'email' => $user->email,
                'scope' => $user->type,
                'iat' => time(),
            ];

            $jwtService = JWTService::getInstance();
            $token = $jwtService->generateToken($tokenPayload);

            return ['token' => $token];
        }

        return ['error' => 'Senha incorreta'];
    }

    private function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function register(UserRegisterRequest $request): bool
    {
        $input = $request->validated();

        $user = User::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => password_hash($input['password'], PASSWORD_BCRYPT),
            'type' => $input['type'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return $user instanceof User;
    }
}
