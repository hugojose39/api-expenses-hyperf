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

    public function index(): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();

        $users = $this->user->where('id', $user->id)->get();

        return $this->response->json($users);
    }

    public function show(int $id): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();

        // Administradores podem ver qualquer usuário
        if ($user->type === 'admin' || $user->id === $id) {
            $userToShow = $this->user->findOrFail($id);
            return $this->response->json($userToShow);
        }

        // Usuários comuns não têm permissão para ver outros usuários
        return $this->response->json(['message' => 'Acesso negado'], 403);
    }

    public function delete(int $id): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();

        // Administradores podem deletar qualquer usuário
        if ($user->type === 'admin' || $user->id === $id) {
            $userToDelete = $this->user->findOrFail($id);
            $userToDelete->delete();
            return $this->response->json(['message' => 'Usuário deletado com sucesso']);
        }

        // Usuários comuns não têm permissão para deletar outros usuários
        return $this->response->json(['message' => 'Acesso negado'], 403);
    }

    public function update(UserRequest $request, int $id): ResponseInterface
    {
        $user = $this->getAuthenticatedUser();

        // Administradores podem atualizar qualquer usuário
        if ($user->type === 'admin' || $user->id === $id) {
            $userToUpdate = $this->user->findOrFail($id);
            $userToUpdate->update($request->validated());
            return $this->response->json($userToUpdate);
        }

        // Usuários comuns não têm permissão para atualizar outros usuários
        return $this->response->json(['message' => 'Acesso negado'], 403);
    }
}
