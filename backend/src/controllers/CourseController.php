<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;

class CourseController
{
    public function getAll(Request $request, Response $response): Response
    {
        $courses = Capsule::table('courses')->get();
        $response->getBody()->write(json_encode($courses));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $id = Capsule::table('courses')->insertGetId([
            'course_code' => $data['course_code'],
            'course_name' => $data['course_name'],
            'credits' => $data['credits'],
            'semester' => $data['semester'],
            'academic_year' => $data['academic_year'],
            'is_active' => $data['is_active'] ?? true
        ]);

        $response->getBody()->write(json_encode(['id' => $id]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        Capsule::table('courses')->where('id', $args['id'])->update([
            'course_code' => $data['course_code'],
            'course_name' => $data['course_name'],
            'credits' => $data['credits'],
            'semester' => $data['semester'],
            'academic_year' => $data['academic_year'],
            'is_active' => $data['is_active'] ?? true
        ]);

        $response->getBody()->write(json_encode(['status' => 'updated']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Capsule::table('courses')->where('id', $args['id'])->delete();
        $response->getBody()->write(json_encode(['status' => 'deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
