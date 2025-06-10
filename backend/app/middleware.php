<?php

use Slim\App;
use App\Application\Middleware\SessionMiddleware;

return function (App $app) {
    // Optional: Session middleware (if using sessions)
    if (class_exists(SessionMiddleware::class)) {
        $app->add(SessionMiddleware::class);
    }

    // Enable CORS for frontend requests
    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);

        // Preflight request (OPTIONS)
        if ($request->getMethod() === 'OPTIONS') {
            $preflight = new \Slim\Psr7\Response();
            return $preflight
                ->withHeader('Access-Control-Allow-Origin', 'http://localhost:8080')
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->withStatus(204);
        }

        // Actual response
        return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:8080')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    });
};
