<?php
// app/Models/Application.php

class Application
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function createApplication($job_id, $name, $email, $phone)
    {
        $stmt = $this->mysqli->prepare("INSERT INTO applications (job_id, name, email, phone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('isss', $job_id, $name, $email, $phone);

        if ($stmt->execute()) {
            return $this->mysqli->insert_id; // Return the ID of the newly created application
        } else {
            return false;
        }
    }
    public function getAllApplications()
    {
        $query = "SELECT * FROM applications ORDER BY created_at DESC"; // Fetch all applications, most recent first
        $result = $this->mysqli->query($query);

        $applications = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $applications[] = $row; // Add each application to the list
            }
        }
        return $applications;
    }
}
