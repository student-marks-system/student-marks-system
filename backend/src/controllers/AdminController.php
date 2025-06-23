<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;

class AdminController
{
    // Fetch mark updates (student_marks table)
    public function getMarkUpdates(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
        $perPage = isset($params['per_page']) ? max(1, min(100, (int)$params['per_page'])) : 10;
        $offset = ($page - 1) * $perPage;

        $studentId = $params['student_id'] ?? null;
        $courseId = $params['course_id'] ?? null;
        $gradedBy = $params['graded_by'] ?? null;
        $startDate = $params['start_date'] ?? null;
        $endDate = $params['end_date'] ?? null;

        $query = Capsule::table('student_marks')
            ->leftJoin('users as students', 'student_marks.student_id', '=', 'students.id')
            ->leftJoin('assessment_components', 'student_marks.assessment_component_id', '=', 'assessment_components.id')
            ->leftJoin('courses', 'assessment_components.course_id', '=', 'courses.id')
            ->leftJoin('users as graders', 'student_marks.graded_by', '=', 'graders.id')
            ->select(
                'student_marks.id',
                'student_marks.student_id',
                'students.full_name as student_name',
                'student_marks.assessment_component_id',
                'assessment_components.component_name',
                'courses.course_code',
                'student_marks.marks_obtained',
                'student_marks.remarks',
                'student_marks.graded_by',
                'graders.full_name as grader_name',
                'student_marks.graded_at',
                'student_marks.is_final',
                'student_marks.created_at'
            );

        if ($studentId) {
            $query->where('student_marks.student_id', $studentId);
        }
        if ($courseId) {
            $query->where('assessment_components.course_id', $courseId);
        }
        if ($gradedBy) {
            $query->where('student_marks.graded_by', $gradedBy);
        }
        if ($startDate) {
            $query->where('student_marks.graded_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('student_marks.graded_at', '<=', $endDate);
        }

        $total = $query->count();
        $logs = $query->orderBy('student_marks.graded_at', 'desc')
                      ->skip($offset)
                      ->take($perPage)
                      ->get();

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'data' => $logs,
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Fetch system logs (audit_log table)
    public function getSystemLogs(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
        $perPage = isset($params['per_page']) ? max(1, min(100, (int)$params['per_page'])) : 10;
        $offset = ($page - 1) * $perPage;

        $userId = $params['user_id'] ?? null;
        $action = $params['action'] ?? null;
        $startDate = $params['start_date'] ?? null;
        $endDate = $params['end_date'] ?? null;

        $query = Capsule::table('audit_log')
            ->leftJoin('users', 'audit_log.user_id', '=', 'users.id')
            ->select(
                'audit_log.id',
                'audit_log.user_id',
                'users.full_name as user_name',
                'audit_log.action',
                'audit_log.table_name',
                'audit_log.record_id',
                'audit_log.old_values',
                'audit_log.new_values',
                'audit_log.ip_address',
                'audit_log.user_agent',
                'audit_log.created_at'
            );

        if ($userId) {
            $query->where('audit_log.user_id', $userId);
        }
        if ($action) {
            $query->where('audit_log.action', $action);
        }
        if ($startDate) {
            $query->where('audit_log.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('audit_log.created_at', '<=', $endDate);
        }

        $total = $query->count();
        $logs = $query->orderBy('audit_log.created_at', 'desc')
                      ->skip($offset)
                      ->take($perPage)
                      ->get();

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'data' => $logs,
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
