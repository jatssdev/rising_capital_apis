<?php
// app/Routes/web.php

// Include the controller file
require_once 'app/controllers/UserController.php';
require_once 'app/controllers/JobController.php';
require_once 'app/middleware/AuthMiddleware.php';

// Remove '/rising' if your app is in a subdirectory
$requestUri = str_replace('/rising', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Trim any leading or trailing slashes
$requestUri = trim($requestUri, '/');



// Home route
if ($requestUri === '') {
    echo "Welcome to the PHP application!";
} elseif ($requestUri === 'api/register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController();
    $controller->register();
} elseif ($requestUri === 'api/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController();
    $controller->login();
}

// API routes
elseif ($requestUri === 'api/users' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    AuthMiddleware::check(); // Check authentication
    $controller = new UserController();
    $controller->getUsers();
}
if ($requestUri === 'api/job/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    AuthMiddleware::check(); // Ensure the user is authenticated
    $controller = new JobController();
    $controller->createJob();
} elseif ($requestUri === 'api/jobs' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    AuthMiddleware::check(); // Ensure the user is authenticated
    $controller = new JobController();
    $controller->getAllJobs();
}
// 404 handler for all undefined routes
else {
    echo $requestUri;
    echo "404 - Page Not Found";
}
