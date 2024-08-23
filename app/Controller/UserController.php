<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\User;
use App\Request\UserRequest;
use Psr\Http\Message\ResponseInterface;

class UserController extends AbstractController
{
    public function __construct(private readonly User $user)
    {
    }

    public function indexAll(): ResponseInterface
    {
        $users = $this->user->all();
        return $this->response->json($users);
    }

    public function show(int $id): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();

        if ($user->type === 'admin' || $user->id === $id) {
            $userToShow = $this->user->findOrFail($id);
            return $this->response->json($userToShow);
        }

        return $this->response->json(['message' => 'Acesso negado'])->withStatus(403);
    }

    public function delete(int $id): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();

        if ($user->type === 'admin' || $user->id === $id) {
            $userToDelete = $this->user->findOrFail($id);
            $userToDelete->delete();
            return $this->response->json(['message' => 'Usuário deletado com sucesso']);
        }

        return $this->response->json(['message' => 'Acesso negado'])->withStatus(403);
    }

    public function update(UserRequest $request, int $id): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();

        if ($user->type === 'admin' || $user->id === $id) {
            $userToUpdate = $this->user->findOrFail($id);
            $userToUpdate->update($request->validated());
            return $this->response->json($userToUpdate);
        }

        return $this->response->json(['message' => 'Acesso negado'])->withStatus(403);
    }
}
