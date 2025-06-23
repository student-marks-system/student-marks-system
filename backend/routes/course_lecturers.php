<?php

use Slim\App;
use App\Controllers\CourseLecturerController;

return function (App $app) {
    $ctrl = new CourseLecturerController();

    $app->get('/course-lecturers', [$ctrl, 'index']);
    $app->post('/course-lecturers', [$ctrl, 'assign']);
    $app->get('/course-lecturers/{id}', [$ctrl, 'get']); // optional
    $app->delete('/course-lecturers/{id}', [$ctrl, 'delete']);
};
