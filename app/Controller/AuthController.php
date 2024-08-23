<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\UserRegisterRequest;
use App\Request\LoginRequest;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Interfaces\LoginRepositoryInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class AuthController
{
    public function __construct(
        private LoginRepositoryInterface $loginRepository,
        private ResponseInterface $response
    ) {
        $this->loginRepository = $loginRepository;
        $this->response = $response;
    }

    public function login(LoginRequest $request): PsrResponseInterface
    {
        return $this->loginRepository->login($request);
    }

    public function register(UserRegisterRequest $request): PsrResponseInterface
    {
        $result = $this->loginRepository->register($request);

        if ($result) {
            return $this->response->json([
                'message' => 'Usuário cadastrado com sucesso.'
            ])->withStatus(201);
        } else {
            return $this->response->json([
                'error' => 'Não foi possível realizar o cadastro.'
            ])->withStatus(500);
        }
    }
}
