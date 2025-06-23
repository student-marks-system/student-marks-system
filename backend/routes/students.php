<?php

use Slim\App;
use App\Controllers\StudentController;

return function (App $app) {
    // Student Management Routes (Lecturer only)
    $app->get('/students', [StudentController::class, 'index']);
    $app->post('/students', [StudentController::class, 'store']);
    $app->put('/students/{id}', [StudentController::class, 'update']);
    $app->delete('/students/{id}', [StudentController::class, 'destroy']);
};
