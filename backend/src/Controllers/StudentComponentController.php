<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as Capsule;

class StudentComponentController
{
    /**
     * GET /students-components
     * Returns list of students and assessment components
     */
    public function index(Request $request, Response $response): Response
    {
        // Fetch students
        $students = Capsule::table('users')
            ->where('user_type', 'student')
            ->select(['id', 'full_name'])
            ->get();

        // Fetch assessment components with course details
        $components = Capsule::table('assessment_components')
            ->join('courses', 'courses.id', '=', 'assessment_components.course_id')
            ->select([
                'assessment_components.id',
                'assessment_components.component_name',
                'courses.course_code',
                'courses.course_name'
            ])
            ->get();

        $data = [
            'students' => $students,
            'components' => $components
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}