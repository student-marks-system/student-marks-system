<?php

use Slim\App;
use App\Controllers\CourseLecturerController;

return function ($app) {
    $ctrl = new CourseLecturerController();

    $app->get('/course-lecturers', [$ctrl, 'index']);
    $app->post('/course-lecturers', [$ctrl, 'assign']);
    $app->get('/course-lecturers/{id}', [$ctrl, 'get']);
    $app->delete('/course-lecturers/{courseId}/{lecturerId}', [$ctrl, 'delete']);
};
