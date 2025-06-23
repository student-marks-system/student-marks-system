<?php

use Slim\App;
use App\Controllers\CourseController;

return function (App $app) {
    $controller = new CourseController();

    $app->get('/courses', [$controller, 'getAll']);
    $app->post('/courses', [$controller, 'create']);
    $app->put('/courses/{id}', [$controller, 'update']);
    $app->delete('/courses/{id}', [$controller, 'delete']);
};
