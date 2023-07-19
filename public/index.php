<?php

include '../autoload.php';

use Config\Cors;
use Config\Route;
use Controllers\UserController;
use Controllers\WelcomeController;
use Lib\Response;

// App::start();
try {
    Cors::allowAll();

    Route::staticFiles();
    Route::get('/api/users/{id}', [UserController::class => 'get']);
    Route::delete('/api/users/{id}', [UserController::class => 'destroy']);
    Route::post('/api/users/{id}', [UserController::class => 'update']);
    Route::get('/api/users', [UserController::class => 'all']);
    Route::post('/api/users', [UserController::class => 'store']);

    Route::get('/', [WelcomeController::class => 'index'], true);
} catch (Exception $err) {
    Response::json(["error" => $err->getMessage()], $err->getCode());
}
