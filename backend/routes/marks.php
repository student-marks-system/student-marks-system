<?php
use Slim\App;
use App\Controllers\MarkController;

return function ($app) {
    $app->post('/marks', [MarkController::class, 'store'])->setName('marks.store');
    $app->get('/marks/test', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode(['status' => 'Marks route is registered']));
        return $response->withHeader('Content-Type', 'application/json');
    });
};