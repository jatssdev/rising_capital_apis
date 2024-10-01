<?php
// app/Controllers/UserController.php

require_once 'app/Models/User.php';
require_once 'config/jwt.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        global $mysqli;
        $this->userModel = new User($mysqli);
    }

    public function getUsers()
    {
        $users = $this->userModel->getAllUsers();
        header('Content-Type: application/json');
        echo json_encode($users);
    }
    public function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['name']) && isset($data['email']) && isset($data['password'])) {
            $name = $data['name'];
            $email = $data['email'];
            $password = password_hash($data['password'], PASSWORD_BCRYPT); // Hash password for security

            $stmt = $this->userModel->createUser($name, $email, $password);
            if ($stmt) {
                echo json_encode(['status' => 'success', 'message' => 'User registered']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    }
    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['email']) && isset($data['password'])) {
            $email = $data['email'];
            $password = $data['password'];

            $user = $this->userModel->getUserByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                // Generate JWT token
                $token = generate_jwt($user['id']);
                echo json_encode(['status' => 'success', 'token' => $token,'user_id'=>$user['id']]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    }





}
