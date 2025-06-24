<?php

use Slim\App;
use App\Controllers\AdminController;

return function ($app) {
    $ctrl = new AdminController();

    // Middleware removed can be added later with authentication
    
    // System logs endpoint
    $app->get('/admin/systemlogs', [$ctrl, 'getSystemLogs']);

    // Mark updates endpoint
    $app->get('/admin/mark-updates', [$ctrl, 'getMarkUpdates']);
};
