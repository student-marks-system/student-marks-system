<?php

use Slim\App;
use App\Controllers\AuthController;

return function ($app) {
    $app->group('/auth', function ($group) {
        $group->post('/login', [AuthController::class, 'login']);
        $group->post('/logout', [AuthController::class, 'logout']);
        $group->post('/refresh', [AuthController::class, 'refreshToken']);
    });

    // Removed duplicate /user/profile route to avoid conflict
}; 