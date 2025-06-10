<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

// Add routing and body parsing
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Load middleware (CORS, sessions, etc.)
(require __DIR__ . '/../app/middleware.php')($app);

// Load routes (login, API endpoints)
(require __DIR__ . '/../app/routes.php')($app);

$app->run();
