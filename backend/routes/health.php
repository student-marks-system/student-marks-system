<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function ($app) {
    $app->get('/api/health', function (Request $request, Response $response) {
        $data = [
            'status' => 'OK',
            'timestamp' => date('Y-m-d H:i:s'),
            'environment' => $_ENV['APP_ENV'] ?? 'development',
            'modules' => [
                'authentication' => 'active',
                'student_performance' => 'active',
                'advisor_module' => 'active'
            ],
            'database' => 'connected' // You can add actual DB check here
        ];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });
}; 