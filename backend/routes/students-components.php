<?php
use Slim\App;
use App\Controllers\StudentComponentController;

return function ($app) {
    $app->get('/students-components', [StudentComponentController::class, 'index'])->setName('students-components.index');
};