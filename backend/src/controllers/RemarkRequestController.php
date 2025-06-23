<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;

class RemarkRequestController
{
    public function submitRequest(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $id = Capsule::table('remark_requests')->insertGetId([
            'student_id' => $data['student_id'],
            'assessment_component_id' => $data['assessment_component_id'],
            'original_marks' => $data['original_marks'],
            'justification' => $data['justification'],
            'status' => 'pending',
            'requested_at' => date('Y-m-d H:i:s'), // ✅ native PHP timestamp
        ]);

        $response->getBody()->write(json_encode(['id' => $id, 'message' => 'Remark request submitted']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getStudentRequests(Request $request, Response $response, array $args): Response
    {
        $requests = Capsule::table('remark_requests')
            ->where('student_id', $args['student_id'])
            ->get();

        $response->getBody()->write(json_encode($requests));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getRequestsForLecturer(Request $request, Response $response, array $args): Response
    {
        $requests = Capsule::table('remark_requests as rr')
            ->join('assessment_components as ac', 'rr.assessment_component_id', '=', 'ac.id')
            ->join('users as u', 'rr.student_id', '=', 'u.id')
            ->where('ac.course_id', $args['course_id'])
            ->select('rr.*', 'u.full_name', 'ac.component_name')
            ->get();

        $response->getBody()->write(json_encode($requests));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function reviewRequest(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        Capsule::table('remark_requests')
            ->where('id', $args['id'])
            ->update([
                'status' => $data['status'],
                'reviewed_by' => $data['reviewed_by'],
                'review_comments' => $data['review_comments'],
                'new_marks' => $data['new_marks'],
                'reviewed_at' => date('Y-m-d H:i:s'), // ✅ native PHP timestamp
            ]);

        $response->getBody()->write(json_encode(['message' => 'Remark request reviewed']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}


