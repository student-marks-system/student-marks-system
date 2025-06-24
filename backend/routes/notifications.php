<?php
use Slim\App;
use App\Controllers\NotificationController;

return function ($app) {
    $app->get('/notifications', [NotificationController::class, 'index']);
    $app->delete('/notifications/{id}', [NotificationController::class, 'delete']);
};