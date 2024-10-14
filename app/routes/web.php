<?php
// app/Routes/web.php

// Include the controller file
require_once 'app/controllers/UserController.php';
require_once 'app/controllers/JobApplicationController.php';
require_once 'app/controllers/JobController.php';
require_once 'app/controllers/CourseController.php';
require_once 'app/middleware/AuthMiddleware.php';

// Remove '/rising' if your app is   a subdirectory
$requestUri = str_replace('/jatssdev', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

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
} elseif ($requestUri === 'api/course/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    AuthMiddleware::check();  // Ensure the user is authenticated
    $controller = new CourseController();
    $controller->createCourse();
} elseif ($requestUri === 'api/courses' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    AuthMiddleware::check();  // Ensure the user is authenticated
    $controller = new CourseController();
    $controller->getAllCourses();
} elseif ($requestUri === 'api/job/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    AuthMiddleware::check(); // Ensure the user is authenticated
    $controller = new JobController();
    $controller->createJob();
} elseif ($requestUri === 'api/jobs' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    AuthMiddleware::check(); // Ensure the user is authenticated
    $controller = new JobController();
    $controller->getAllJobs();
} elseif ($requestUri === 'api/job/apply' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new JobApplicationController();
    $controller->applyForJob();
} elseif ($requestUri === 'api/job/applications' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    AuthMiddleware::check(); // Ensure the user is authenticated
    $controller = new JobApplicationController();
    $controller->getAllApplications();
} elseif (preg_match('/^api\/job\/delete\/(\d+)$/', $requestUri, $matches) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    AuthMiddleware::check(); // Ensure the user is authenticated

    $job_id = $matches[1];  // Capture the job ID from the URL
    $controller = new JobController();
    $controller->deleteJob($job_id);
} elseif (preg_match('/^api\/job\/update\/(\d+)$/', $requestUri, $matches) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    AuthMiddleware::check(); // Ensure the user is authenticated
    $job_id = $matches[1];  // Capture the job ID from the URL
    $controller = new JobController();
    $controller->updateJob($job_id);
} elseif (preg_match('/^api\/job\/(\d+)$/', $requestUri, $matches) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    AuthMiddleware::check(); // Ensure the user is authenticated
    $job_id = $matches[1];  // Capture the job ID from the URL
    $controller = new JobController();
    $controller->getJobById($job_id);
}
// 404 handler for all undefined routes
else {
    echo $requestUri;
    echo "404 - Page Not Found";
}
