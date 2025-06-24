<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface  as Request;
use Illuminate\Database\Capsule\Manager        as Capsule;

class EnrollmentController
{
    /**
     * GET /course_enrollments
     * GET /course_enrollments/course/{course_id}
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $qb = Capsule::table('course_enrollments')
            ->join('users',   'users.id',   '=', 'course_enrollments.student_id')
            ->join('courses', 'courses.id', '=', 'course_enrollments.course_id')
            ->select([
                'course_enrollments.id',
                'users.id   as student_id',
                'users.full_name  as student_name',
                'courses.id  as course_id',
                'courses.course_code',
                'courses.course_name',
                'course_enrollments.status',
                'course_enrollments.enrollment_date'
            ]);

        // if route has {course_id}, filter
        if (isset($args['course_id'])) {
            $qb->where('course_enrollments.course_id', $args['course_id']);
        }

        $records = $qb->orderBy('course_enrollments.enrollment_date', 'desc')
                      ->get();

        $response->getBody()->write(json_encode($records));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * POST /course_enrollments
     * body: { student_id, course_id, status? }
     */
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // validate
        $studentId = $data['student_id'] ?? null;
        $courseId  = $data['course_id']  ?? null;
        if (! $studentId || ! $courseId) {
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode([
                    'error' => 'student_id and course_id are required'
                ]));
        }

        // prevent duplicates (unique key on student_id+course_id)
        $exists = Capsule::table('course_enrollments')
            ->where('student_id', $studentId)
            ->where('course_id',  $courseId)
            ->exists();

        if ($exists) {
            return $response
                ->withStatus(409)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode(['error' => 'Already enrolled']));
        }

        // insert
        $id = Capsule::table('course_enrollments')->insertGetId([
            'student_id'      => $studentId,
            'course_id'       => $courseId,
            'status'          => $data['status'] ?? 'active',
            'enrollment_date' => date('Y-m-d H:i:s'),
        ]);

        $response->getBody()->write(json_encode(['id' => $id, 'status' => 'enrolled']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * DELETE /course_enrollments/{id}
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'] ?? null;
        if (! $id) {
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode(['error' => 'Enrollment ID required']));
        }

        Capsule::table('course_enrollments')
            ->where('id', $id)
            ->delete();

        $response->getBody()->write(json_encode(['status' => 'deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
