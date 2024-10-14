<?php
// app/Controllers/JobApplicationController.php

require_once 'app/Models/Application.php';
require_once 'config/email.php'; // For sending email

class JobApplicationController
{
    private $applicationModel;
    public function __construct()
    {
        global $mysqli;
        $this->applicationModel = new Application($mysqli);
    }
    public function applyForJob()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        if (isset($data['job_id']) && isset($data['name']) && isset($data['email']) && isset($data['phone'])) {
            $job_id = $data['course_id'];
            $name = $data['name'];
            $email = $data['email'];
            $phone = $data['phone'];
            // Store application details in the database
            $application_id = $this->applicationModel->createApplication($job_id, $name, $email, $phone);
            if ($application_id) {
                // Send email notification to admin
                sendApplicationEmail($application_id, $job_id, $name, $email, $phone);

                echo json_encode(['status' => 'success', 'message' => 'Application submitted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Application submission failed']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    }
    public function getAllApplications()
    {
        $applications = $this->applicationModel->getAllApplications();

        // Return applications in JSON format
        header('Content-Type: application/json');
        echo json_encode($applications);
    }
}
