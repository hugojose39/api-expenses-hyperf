<?php

namespace App\Interfaces;

use App\Request\LoginRequest;
use App\Request\UserRegisterRequest;

interface LoginRepositoryInterface 
{
    public function login(LoginRequest $request);
    public function register(UserRegisterRequest $request);
}