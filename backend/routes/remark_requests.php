<?php

use App\Controllers\RemarkRequestController;

return function ($app) {
    $controller = new RemarkRequestController();

    $app->group('/api/remark-requests', function ($group) use ($controller) {
        $group->post('/submit', [$controller, 'submitRequest']);
        $group->get('/student/{student_id}', [$controller, 'getStudentRequests']);
        $group->get('/course/{course_id}', [$controller, 'getRequestsForLecturer']);
        $group->post('/review/{id}', [$controller, 'reviewRequest']);
    });
};
