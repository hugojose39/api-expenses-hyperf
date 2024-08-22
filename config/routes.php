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
use App\Controller\CardController;
use App\Controller\ExpenseController;
use App\Controller\UserController;
use App\Middleware\AdminMiddleware;
use App\Middleware\AuthMiddleware;
use Hyperf\HttpServer\Router\Router;
use Swoole\Server\Admin;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::addGroup('/api', function () {
    Router::post('/login', [AuthController::class, 'login']);
    Router::post('/register', [AuthController::class, 'register']);

    Router::addGroup('/users', function () {
        Router::get('/indexAll', [UserController::class, 'indexAll'], ['middleware' => [AdminMiddleware::class]]);
        Router::get('', [UserController::class, 'index'], ['middleware' => [AuthMiddleware::class]]);
        Router::get('/{id}', [UserController::class, 'show'], ['middleware' => [AuthMiddleware::class]]);
        Router::post('', [UserController::class, 'store'], ['middleware' => [AuthMiddleware::class]]);
        Router::put('/{id}', [UserController::class, 'update'], ['middleware' => [AuthMiddleware::class]]);
        Router::delete('/{id}', [UserController::class, 'delete'], ['middleware' => [AuthMiddleware::class]]);
    });

    Router::addGroup('/cards', function () {
        Router::get('/indexAll', [CardController::class, 'indexAll'], ['middleware' => [AdminMiddleware::class]]);
        Router::get('', [CardController::class, 'index'], ['middleware' => [AuthMiddleware::class]]);
        Router::get('/{id}', [CardController::class, 'show'], ['middleware' => [AuthMiddleware::class]]);
        Router::post('', [CardController::class, 'store'], ['middleware' => [AuthMiddleware::class]]);
        Router::put('/{id}', [CardController::class, 'update'], ['middleware' => [AuthMiddleware::class]]);
        Router::delete('/{id}', [CardController::class, 'delete'], ['middleware' => [AuthMiddleware::class]]);
    });

    Router::addGroup('/expenses', function () {
        Router::get('/indexAll', [ExpenseController::class, 'indexAll'], ['middleware' => [AdminMiddleware::class]]);
        Router::get('', [ExpenseController::class, 'index'], ['middleware' => [AuthMiddleware::class]]);
        Router::get('/{id}', [ExpenseController::class, 'show'], ['middleware' => [AuthMiddleware::class]]);
        Router::post('', [ExpenseController::class, 'store'], ['middleware' => [AuthMiddleware::class]]);
        Router::put('/{id}', [ExpenseController::class, 'update'], ['middleware' => [AuthMiddleware::class]]);
        Router::delete('/{id}', [ExpenseController::class, 'delete'], ['middleware' => [AuthMiddleware::class]]);
    });
});

Router::get('/favicon.ico', function () {
    return '';
});
