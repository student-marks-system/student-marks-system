<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\StudentPerformanceController;
use App\Controllers\AuthController;

return function (App $app) {

    // Health check endpoint (no auth required)
    $app->get('/api/health', function (Request $request, Response $response) {
        $data = [
            'status' => 'OK',
            'timestamp' => date('Y-m-d H:i:s'),
            'environment' => $_ENV['APP_ENV'] ?? 'development',
            'modules' => [
                'authentication' => 'active',
                'student_performance' => 'active',
                'advisor_module' => 'active'
            ],
            'database' => 'connected' // You can add actual DB check here
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // Test endpoint (no auth required)
    $app->get('/api/test', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode([
            'message' => 'API is working!',
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $request->getMethod(),
            'cors_headers' => [
                'Access-Control-Allow-Origin' => $_ENV['FRONTEND_URL'] ?? 'http://localhost:8081'
            ]
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // ========================================
    // AUTHENTICATION ROUTES (No auth required)
    // ========================================

    $app->group('/api/auth', function ($group) {
        $group->post('/login', [AuthController::class, 'login']);
        $group->post('/logout', [AuthController::class, 'logout']);
        $group->post('/refresh', [AuthController::class, 'refreshToken']);
    });

    // Authentication middleware for protected routes
    $authMiddleware = \App\Middleware\JWTAuthMiddleware::class;


    // Group all protected API routes
    $app->group('/api', function ($group) use ($authMiddleware) {
        // User profile endpoint - returns actual logged-in user data
        $group->get('/user/profile', [AuthController::class, 'getUserProfile'])->add($authMiddleware);

        // Test protected endpoint
        $group->get('/test-protected', function (Request $request, Response $response) {
            $userId = $request->getAttribute('user_id');
            $userRole = $request->getAttribute('user_role');

            $response->getBody()->write(json_encode([
                'message' => 'This is a protected endpoint',
                'user_id' => $userId,
                'user_role' => $userRole,
                'timestamp' => date('Y-m-d H:i:s')
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        })->add($authMiddleware);

        // User profile routes (protected)
        // $group->get('/user/profile', 'App\Controllers\AuthController:getUserProfile')->add($authMiddleware);
        $group->put('/auth/change-password', [AuthController::class, 'changePassword'])->add($authMiddleware);

        // Student Performance test route

        // ========================================
        // STUDENT PERFORMANCE MODULE ROUTES
        // ========================================

        // Get student's component-wise marks for a specific course
        $group->get(
            '/student/{studentId}/course/{courseId}/marks',
            'App\Controllers\StudentPerformanceController:getStudentCourseMarks'
        )
            ->add($authMiddleware);

        // Get student's full mark breakdown across all courses
        $group->get(
            '/student/{studentId}/marks/breakdown',
            'App\Controllers\StudentPerformanceController:getStudentFullBreakdown'
        )
            ->add($authMiddleware);

        // Get anonymous comparison with coursemates
        $group->get(
            '/course/{courseId}/marks/comparison',
            'App\Controllers\StudentPerformanceController:getCourseComparison'
        )
            ->add($authMiddleware);

        // Get student's class rank and percentile
        $group->get(
            '/student/{studentId}/course/{courseId}/rank',
            'App\Controllers\StudentPerformanceController:getStudentRank'
        )
            ->add($authMiddleware);

        // Get performance trends over time
        $group->get(
            '/student/{studentId}/performance/trends',
            'App\Controllers\StudentPerformanceController:getPerformanceTrends'
        )
            ->add($authMiddleware);

        // What-if grade simulation
        $group->post(
            '/student/{studentId}/what-if-simulation',
            'App\Controllers\StudentPerformanceController:whatIfSimulation'
        )
            ->add($authMiddleware);

        // Get class averages per component
        $group->get(
            '/course/{courseId}/averages',
            'App\Controllers\StudentPerformanceController:getClassAverages'
        )
            ->add($authMiddleware);

        // ========================================
        // LECTURER MODULE ROUTES
        // ========================================

        // Get lecturer's dashboard overview
        $group->get(
            '/lecturer/{lecturerId}/dashboard',
            'App\Controllers\LecturerController:getDashboard'
        )
            ->add($authMiddleware);

        // Get students in a specific course
        $group->get(
            '/lecturer/{lecturerId}/course/{courseId}/students',
            'App\Controllers\LecturerController:getCourseStudents'
        )
            ->add($authMiddleware);

        // Get assessment components for a course
        $group->get(
            '/lecturer/{lecturerId}/course/{courseId}/components',
            'App\Controllers\LecturerController:getCourseComponents'
        )
            ->add($authMiddleware);

        // Create or update assessment component
        $group->post(
            '/lecturer/{lecturerId}/course/{courseId}/components',
            'App\Controllers\LecturerController:createUpdateComponent'
        )
            ->add($authMiddleware);

        // Update student marks for a specific component
        $group->put(
            '/lecturer/{lecturerId}/course/{courseId}/student/{studentId}/marks',
            'App\Controllers\LecturerController:updateStudentMarks'
        )
            ->add($authMiddleware);

        // Bulk upload marks via CSV
        $group->post(
            '/lecturer/{lecturerId}/course/{courseId}/marks/bulk-upload',
            'App\Controllers\LecturerController:bulkUploadMarks'
        )
            ->add($authMiddleware);

        // Export course marks as CSV
        $group->get(
            '/lecturer/{lecturerId}/course/{courseId}/marks/export',
            'App\Controllers\LecturerController:exportMarks'
        )
            ->add($authMiddleware);
        //remarks
        $group->put('/lecturer/{lecturerId}/marks/{markId}/remarks', 'App\Controllers\LecturerController:updateRemarks')
            ->add($authMiddleware);


        // Get course analytics
        $group->get(
            '/lecturer/{lecturerId}/course/{courseId}/analytics',
            'App\Controllers\LecturerController:getCourseAnalytics'
        )
            ->add($authMiddleware);

        // Send notification to students
        $group->post(
            '/lecturer/{lecturerId}/course/{courseId}/notifications',
            'App\Controllers\LecturerController:sendNotification'
        )
            ->add($authMiddleware);

        // ========================================
        // ADVISOR MODULE ROUTES
        // ========================================

        // Get list of advisees under supervision
        $group->get(
            '/advisor/{advisorId}/advisees',
            'App\Controllers\AdvisorController:getAdvisees'
        )
            ->add($authMiddleware);

        // Get advisee's detailed academic breakdown
        $group->get(
            '/advisor/{advisorId}/advisee/{studentId}/breakdown',
            'App\Controllers\AdvisorController:getAdviseeBreakdown'
        )
            ->add($authMiddleware);

        // Get at-risk students requiring intervention
        $group->get(
            '/advisor/{advisorId}/at-risk-students',
            'App\Controllers\AdvisorController:getAtRiskStudents'
        )
            ->add($authMiddleware);

        // Add consultation notes/meeting records
        $group->post(
            '/advisor/{advisorId}/advisee/{studentId}/notes',
            'App\Controllers\AdvisorController:addAdviseNote'
        )
            ->add($authMiddleware);

        // Get consultation history for an advisee
        $group->get(
            '/advisor/{advisorId}/advisee/{studentId}/notes',
            'App\Controllers\AdvisorController:getAdviseNotes'
        )
            ->add($authMiddleware);

        // Delete consultation note
        $group->delete(
            '/advisor/{advisorId}/advisee/{studentId}/notes/{noteId}',
            'App\Controllers\AdvisorController:deleteAdviseNote'
        )
            ->add($authMiddleware);

        // Export consultation reports (JSON/CSV)
        $group->get(
            '/advisor/{advisorId}/advisee/{studentId}/report',
            'App\Controllers\AdvisorController:exportConsultationReport'
        )
            ->add($authMiddleware);



    });

    // Catch-all route for undefined API endpoints
    $app->any('/api/{routes:.+}', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode([
            'error' => true,
            'message' => 'API endpoint not found',
            'requested_path' => $request->getUri()->getPath(),
            'method' => $request->getMethod(),
            'available_endpoints' => [
                'health' => 'GET /api/health',
                'test' => 'GET /api/test',
                'login' => 'POST /api/auth/login',
                'student_performance' => '/api/student/{id}/...',
                'advisor_module' => '/api/advisor/{id}/...'
            ]
        ]));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    });

};