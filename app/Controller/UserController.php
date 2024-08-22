<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\User;
use App\Request\UserRequest;
use Psr\Http\Message\ResponseInterface;

class UserController extends AbstractController
{
    public function index(): ResponseInterface
    {
        $users = User::all();
        return $this->response->json($users);
    }

    public function show(int $id): ResponseInterface
    {
        $user = User::findOrFail($id);
        return $this->response->json($user);
    }

    public function delete(int $id): ResponseInterface
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $this->response->json(['message' => 'User deleted successfully']);
    }

    public function update(UserRequest $request, int $id): ResponseInterface
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return $this->response->json($user);
    }
}
