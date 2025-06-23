<?php
use Slim\App;
use App\Controllers\StudentComponentController;

return function (App $app) {
    $app->get('/students-components', [StudentComponentController::class, 'index'])->setName('students-components.index');
};