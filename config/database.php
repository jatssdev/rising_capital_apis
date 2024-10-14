<?php
// config/database.php

// Load environment variables
$dotenv = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($dotenv as $line) {
    list($key, $value) = explode('=', $line, 2);
    putenv(trim($key) . '=' . trim($value));
}

// Database connection using MySQLi
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

$mysqli = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
