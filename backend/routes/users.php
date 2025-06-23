<?php

use Slim\App;
use Illuminate\Database\Capsule\Manager as Capsule;

return function (App $app) {
    // Get all users with user_type = 'lecturer'
    $app->get('/users', function ($request, $response) {
        $type = $request->getQueryParams()['type'] ?? '';

        if ($type === 'lecturer') {
            $lecturers = Capsule::table('users')
                ->where('user_type', 'lecturer')
                ->select('id as lecturer_id', 'full_name as lecturer_name', 'email')
                ->get();
                
            $response->getBody()->write(json_encode($lecturers));
            return $response->withHeader('Content-Type', 'application/json');
        }

        // Optional: handle other user types or return empty
        $response->getBody()->write(json_encode([]));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
