<?php

declare(strict_types=1);

use App\Interfaces\LoginRepositoryInterface;
use App\Repositories\LoginRepository;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    LoginRepositoryInterface::class => LoginRepository::class, 
];
