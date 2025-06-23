<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;

class CourseLecturerController
{
  
    public function index(Request $request, Response $response): Response
    {
        $records = Capsule::table('course_lecturers')
            ->join('users', 'users.id', '=', 'course_lecturers.lecturer_id')
            ->join('courses', 'courses.id', '=', 'course_lecturers.course_id')
            ->select(
                'course_lecturers.id',
                'course_lecturers.role',
                'course_lecturers.assigned_at',
                'users.id as lecturer_id',
                'users.full_name as lecturer_name',
                'courses.id as course_id',
                'courses.course_code',
                'courses.course_name'
            )
            ->orderBy('course_lecturers.assigned_at', 'desc')
            ->get();

        $response->getBody()->write(json_encode($records));
        return $response->withHeader('Content-Type', 'application/json');
    }

    
public function assign(Request $request, Response $response): Response
{
    $data = $request->getParsedBody();

    // Log incoming data for debugging
    error_log('Assign data: ' . json_encode($data));

    // Validate input
    if (empty($data['lecturer_id']) || empty($data['course_id']) || empty($data['role'])) {
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'message' => 'Missing required fields: lecturer_id, course_id, or role'
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    try {
        Capsule::table('course_lecturers')->insert([
            'lecturer_id' => $data['lecturer_id'],
            'course_id' => $data['course_id'],
            'role' => $data['role'],
            'assigned_at' => date('Y-m-d H:i:s'),
        ]);

        $response->getBody()->write(json_encode(['status' => 'assigned']));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (\Exception $e) {
        // Log the error
        error_log('Failed to assign lecturer: ' . $e->getMessage());

        // Return detailed error
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'message' => 'Failed to assign lecturer: ' . $e->getMessage()
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
}



    // Delete assignment
    public function delete(Request $request, Response $response, $args): Response
    {
        Capsule::table('course_lecturers')->where('id', $args['id'])->delete();

        $response->getBody()->write(json_encode(['status' => 'deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // (Optional) Get single record
    public function get(Request $request, Response $response, $args): Response
    {
        $record = Capsule::table('course_lecturers')->where('id', $args['id'])->first();

        $response->getBody()->write(json_encode($record));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
