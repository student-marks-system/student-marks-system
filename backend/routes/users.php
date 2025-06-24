<?php

use Slim\App;
use App\Controllers\UserController;

return function ($app) {
    $app->get('/users', [UserController::class, 'index']);
    $app->post('/users', [UserController::class, 'store']);
    $app->put('/users/{id}', [UserController::class, 'update']);
    $app->delete('/users/{id}', [UserController::class, 'destroy']);
};
