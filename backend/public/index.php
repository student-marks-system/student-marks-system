<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/db.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

// ✅ CORS + preflight handling middleware
$app->add(function ($request, $handler) {
    $origin = $request->getHeaderLine('Origin') ?: '*';

    // Handle preflight request
    if ($request->getMethod() === 'OPTIONS') {
        $response = new \Slim\Psr7\Response(204);
    } else {
        $response = $handler->handle($request);
    }

    return $response
        ->withHeader('Access-Control-Allow-Origin', $origin)
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Credentials', 'true');
});

(require __DIR__ . '/../routes/courses.php')($app);
(require __DIR__ . '/../routes/course_lecturers.php')($app);
(require __DIR__ . '/../routes/users.php')($app);

(require __DIR__ . '/../routes/admin.php')($app);
(require __DIR__ . '/../routes/remark_requests.php')($app);

$app->run();
