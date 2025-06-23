<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as Capsule;

class MarkController
{
    /**
     * POST /marks
     * body: { student_id, assessment_component_id, marks_obtained, remarks?, is_final? }
     */
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // Log request data for debugging
        error_log('POST /marks received: ' . json_encode($data));

        // Validate required fields
        $studentId = $data['student_id'] ?? null;
        $assessmentComponentId = $data['assessment_component_id'] ?? null;
        $marksObtained = $data['marks_obtained'] ?? null;
        $remarks = $data['remarks'] ?? null;
        $isFinal = $data['is_final'] ?? false;

        if (!$studentId || !$assessmentComponentId || $marksObtained === null) {
            $error = 'student_id, assessment_component_id, and marks_obtained are required';
            error_log('Validation error: ' . $error);
            $response->getBody()->write(json_encode(['error' => $error]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Validate student (must exist and be a student)
        $student = Capsule::table('users')
            ->where('id', $studentId)
            ->where('user_type', 'student')
            ->first();

        if (!$student) {
            $error = 'Invalid or non-student user';
            error_log('Validation error: ' . $error);
            $response->getBody()->write(json_encode(['error' => $error]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Validate assessment component
        $component = Capsule::table('assessment_components')
            ->where('id', $assessmentComponentId)
            ->first();

        if (!$component) {
            $error = 'Invalid assessment component';
            error_log('Validation error: ' . $error);
            $response->getBody()->write(json_encode(['error' => $error]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Check if mark exists
        $existingMark = Capsule::table('student_marks')
            ->where('student_id', $studentId)
            ->where('assessment_component_id', $assessmentComponentId)
            ->first();

        $now = date('Y-m-d H:i:s');

        if ($existingMark) {
            // Update existing mark
            Capsule::table('student_marks')
                ->where('id', $existingMark->id)
                ->update([
                    'marks_obtained' => $marksObtained,
                    'remarks' => $remarks,
                    'graded_at' => $now,
                    'is_final' => $isFinal,
                    'updated_at' => $now,
                ]);
        } else {
            // Insert new mark
            Capsule::table('student_marks')->insert([
                'student_id' => $studentId,
                'assessment_component_id' => $assessmentComponentId,
                'marks_obtained' => $marksObtained,
                'remarks' => $remarks,
                'graded_at' => $now,
                'is_final' => $isFinal,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Create notification
        $course = Capsule::table('courses')
            ->where('id', $component->course_id)
            ->first();

        Capsule::table('notifications')->insert([
            'user_id' => $studentId,
            'title' => "Mark Updated for {$course->course_code}",
            'message' => "Your marks for {$component->component_name} in {$course->course_name} have been updated to {$marksObtained}.",
            'type' => 'mark_update',
            'is_read' => false,
            'created_at' => $now,
        ]);

        $response->getBody()->write(json_encode(['status' => 'marks_saved']));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}