<?php

declare(strict_types=1);

use App\Interfaces\CardRepositoryInterface;
use App\Repositories\CardRepository;
use App\Interfaces\ExpenseRepositoryInterface;
use App\Repositories\ExpenseRepository;
use App\Interfaces\EmailServiceInterface;
use App\Services\EmailService;
use App\Interfaces\LoginRepositoryInterface;
use App\Repositories\LoginRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    CardRepositoryInterface::class => CardRepository::class,
    ExpenseRepositoryInterface::class => ExpenseRepository::class,
    LoginRepositoryInterface::class => LoginRepository::class,
    UserRepositoryInterface::class => UserRepository::class,
    EmailServiceInterface::class => EmailService::class,
];
