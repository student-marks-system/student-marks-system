<?php
use Slim\App;
use App\Controllers\EnrollmentController;

return function (App $app) {
    // List all OR by course
    $app->get('/course_enrollments', [EnrollmentController::class, 'index']);
    $app->get('/course_enrollments/course/{course_id}', [EnrollmentController::class, 'index']);

    // Create
    $app->post('/course_enrollments', [EnrollmentController::class, 'store']);

    // Delete
    $app->delete('/course_enrollments/{id}', [EnrollmentController::class, 'delete']);
};
