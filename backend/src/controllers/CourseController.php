<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CourseController extends BaseController
{
    public function getAll(Request $request, Response $response): Response
    {
        try {
            $stmt = $this->db->query("SELECT * FROM courses");
            $courses = $stmt->fetchAll();
            return $this->successResponse($response, $courses);
        } catch (\PDOException $e) {
            return $this->errorResponse($response, "Failed to fetch courses: " . $e->getMessage(), 500);
        }
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            // Validate required fields
            $required = ['course_code', 'course_name', 'credits', 'semester', 'academic_year'];
            $missing = $this->validateRequired($data, $required);
            if (!empty($missing)) {
                return $this->errorResponse($response, "Missing required fields: " . implode(', ', $missing), 400);
            }

            $sql = "INSERT INTO courses (course_code, course_name, credits, semester, academic_year, is_active) 
                   VALUES (:course_code, :course_name, :credits, :semester, :academic_year, :is_active)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'course_code' => $data['course_code'],
                'course_name' => $data['course_name'],
                'credits' => $data['credits'],
                'semester' => $data['semester'],
                'academic_year' => $data['academic_year'],
                'is_active' => $data['is_active'] ?? true
            ]);

            $id = $this->db->lastInsertId();
            return $this->successResponse($response, ['id' => $id], 'Course created successfully');
        } catch (\PDOException $e) {
            return $this->errorResponse($response, "Failed to create course: " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        try {
            $data = $request->getParsedBody();
            $id = $args['id'];

            // Validate required fields
            $required = ['course_code', 'course_name', 'credits', 'semester', 'academic_year'];
            $missing = $this->validateRequired($data, $required);
            if (!empty($missing)) {
                return $this->errorResponse($response, "Missing required fields: " . implode(', ', $missing), 400);
            }

            $sql = "UPDATE courses SET 
                   course_code = :course_code,
                   course_name = :course_name,
                   credits = :credits,
                   semester = :semester,
                   academic_year = :academic_year,
                   is_active = :is_active
                   WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                'id' => $id,
                'course_code' => $data['course_code'],
                'course_name' => $data['course_name'],
                'credits' => $data['credits'],
                'semester' => $data['semester'],
                'academic_year' => $data['academic_year'],
                'is_active' => $data['is_active'] ?? true
            ]);

            if (!$success) {
                return $this->errorResponse($response, "Course not found", 404);
            }

            return $this->successResponse($response, null, 'Course updated successfully');
        } catch (\PDOException $e) {
            return $this->errorResponse($response, "Failed to update course: " . $e->getMessage(), 500);
        }
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        try {
            $id = $args['id'];
            $sql = "DELETE FROM courses WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute(['id' => $id]);

            if (!$success) {
                return $this->errorResponse($response, "Course not found", 404);
            }

            return $this->successResponse($response, null, 'Course deleted successfully');
        } catch (\PDOException $e) {
            return $this->errorResponse($response, "Failed to delete course: " . $e->getMessage(), 500);
        }
    }
}
