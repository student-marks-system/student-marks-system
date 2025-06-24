<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use DI\Container;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Create Container
$container = new Container();

// Set container to create App with on AppFactory
AppFactory::setContainer($container);
$app = AppFactory::create();

// Parse json, form data and xml
$app->addBodyParsingMiddleware();

// Add Routing Middleware
$app->addRoutingMiddleware();

// CORS Middleware - Enable cross-origin requests from frontend
// This must be applied BEFORE routes are processed
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    
    // Get the correct frontend URL from environment
    $frontendUrl = $_ENV['FRONTEND_URL'] ?? 'http://localhost:8081';
    
    return $response
        ->withHeader('Access-Control-Allow-Origin', $frontendUrl)
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
        ->withHeader('Access-Control-Allow-Credentials', 'true');
});

// Handle preflight OPTIONS requests BEFORE routing
// This is crucial for CORS to work properly
$app->options('/{routes:.+}', function ($request, $response, $args) {
    // Return response with CORS headers already set by middleware above
    return $response;
});

// Add a simple test route to verify server is working
$app->get('/', function ($request, $response) {
    $response->getBody()->write(json_encode([
        'message' => 'Student Marks System API',
        'version' => '1.0.0',
        'status' => 'running',
        'timestamp' => date('Y-m-d H:i:s'),
        'cors_enabled' => true,
        'frontend_url' => $_ENV['FRONTEND_URL'] ?? 'http://localhost:8081'
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});

// Load routes from separate file
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

// Error Middleware with better error handling
$errorMiddleware = $app->addErrorMiddleware(
    $_ENV['APP_DEBUG'] === 'true', // Display error details
    true, // Log errors
    true  // Log error details
);

// Custom error handler
$errorHandler = function (
    \Psr\Http\Message\ServerRequestInterface $request,
    \Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $statusCode = 500;
    $errorMessage = 'Internal server error';
    
    if ($exception instanceof \Slim\Exception\HttpNotFoundException) {
        $statusCode = 404;
        $errorMessage = 'Resource not found';
    } elseif ($exception instanceof \Slim\Exception\HttpMethodNotAllowedException) {
        $statusCode = 405;
        $errorMessage = 'Method not allowed';
    }

    $response = $app->getResponseFactory()->createResponse($statusCode);
    $errorData = [
        'error' => true,
        'message' => $errorMessage,
        'status_code' => $statusCode
    ];

    if ($displayErrorDetails) {
        $errorData['details'] = $exception->getMessage();
        $errorData['file'] = $exception->getFile();
        $errorData['line'] = $exception->getLine();
        $errorData['trace'] = $exception->getTraceAsString();
    }

    $response->getBody()->write(json_encode($errorData, JSON_PRETTY_PRINT));
    
    return $response->withHeader('Content-Type', 'application/json');
};

$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run the application
$app->run();