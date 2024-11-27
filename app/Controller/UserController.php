<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interfaces\UserRepositoryInterface;
use App\Model\User;
use App\Request\UserRequest;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class UserController extends AbstractController
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function indexAll(): PsrResponseInterface
    {
        $users = $this->userRepository->all();
        return $this->response->json($users);
    }

    public function show(int $id): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();

        if ($user->type === 'admin' || $user->id === $id) {
            $userToShow = $this->userRepository->findOrFail($id);
            return $this->response->json($userToShow);
        }

        return $this->response->json(['message' => 'Acesso negado'])->withStatus(403);
    }

    public function update(UserRequest $request, int $id): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();

        if ($user->type === 'admin' || $user->id === $id) {
            $userToUpdate = $this->userRepository->findOrFail($id);
            $updatedUser = $this->userRepository->update($userToUpdate, $request->validated());
            return $this->response->json($updatedUser);
        }

        return $this->response->json(['message' => 'Acesso negado'])->withStatus(403);
    }

    public function delete(int $id): PsrResponseInterface
    {
        $user = $this->getAuthenticatedUser();

        if ($user->type === 'admin' || $user->id === $id) {
            $userToDelete = $this->userRepository->findOrFail($id);
            $this->userRepository->delete($userToDelete);
            return $this->response->json(['message' => 'Usuário deletado com sucesso']);
        }

        return $this->response->json(['message' => 'Acesso negado'])->withStatus(403);
    }
}
