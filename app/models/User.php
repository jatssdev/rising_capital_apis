<?php
// app/Models/User.php

class User
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function getAllUsers()
    {
        $query = "SELECT id, name, email, created_at FROM users"; // Exclude password field
        $result = $this->mysqli->query($query);

        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['name'] = trim($row['name']);
                $row['email'] = trim($row['email']);
                $users[] = $row;
            }
        }
        return $users;
    }

    public function createUser($name, $email, $password)
    {
        $stmt = $this->mysqli->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $password);
        return $stmt->execute();
    }
    public function getUserByEmail($email)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


}
