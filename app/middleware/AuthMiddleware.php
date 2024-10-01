<?php
// app/Middleware/AuthMiddleware.php

require_once 'config/jwt.php';

class AuthMiddleware
{
    public static function check()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            // Remove "Bearer " from the Authorization header
            $jwt = trim(str_replace('Bearer ', '', $headers['Authorization']));
            $user_id = validate_jwt($jwt);

            if ($user_id) {
                return $user_id; // Token is valid, return user_id
            } else {
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token']);
                exit();
            }
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Authorization token not provided']);
            exit();
        }
    }
}
