<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Controller\AuthController;
use App\Middleware\AuthMiddleware;
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::addGroup('/api', function () {
    Router::post('/login', [AuthController::class, 'login']);
    Router::post('/register', [AuthController::class, 'register']);

    // Router::get('', [UserController::class, 'index']);
    // Router::get('/{id}', [UserController::class, 'show']);
    // Router::post('', [UserController::class, 'store']);
    // Router::delete('/{id}', [UserController::class, 'delete']);
    // Router::get('/categorys/get', [\App\Controller\CategorysController::class, 'get'], ['middleware' => [AuthMiddleware::class]]);
});

Router::get('/favicon.ico', function () {
    return '';
});
