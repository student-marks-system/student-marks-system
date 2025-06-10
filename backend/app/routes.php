<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;



return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

     $app->post('/api/login', \App\Application\Actions\User\LoginAction::class);

    $app->get('/api/secret', function (Request $request, Response $response) {
        $user = $request->getAttribute('user');
        $response->getBody()->write(json_encode([
            'message' => "Hello, {$user['username']}! You are a {$user['user_type']}."
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    })->add(\App\Application\Middleware\RoleMiddleware::class);

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

};
