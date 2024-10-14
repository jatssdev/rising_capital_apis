<?php
header('Content-Type: application/json');


// index.php or web.php (before routing logic)

// Allow cross-origin requests
header("Access-Control-Allow-Origin: *"); // Allow all domains to access this API (use specific domain if needed)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Allow specific HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow specific headers

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200); // Return success for preflight request
    exit();
}

// Proceed with routing or API logic
require_once 'config/database.php';
require_once 'app/Routes/web.php';
