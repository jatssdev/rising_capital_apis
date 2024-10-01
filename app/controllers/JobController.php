<?php
// app/Controllers/JobController.php

require_once 'app/Models/Job.php';

class JobController
{
    private $jobModel;

    public function __construct()
    {
        global $mysqli;
        $this->jobModel = new Job($mysqli);
    }

    public function createJob()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        if (isset($data['title']) && isset($data['description']) && isset($data['experience']) && isset($data['education']) && isset($data['location'])) {
            $title = $data['title'];
            $description = $data['description'];
            $experience = $data['experience'];
            $education = $data['education'];
            $location = $data['location']; // Expecting an array of strings (job locations)

            // Call the model method to insert the job
            $job_id = $this->jobModel->createJob($title, $description, $experience, $education, $location);

            if ($job_id) {
                echo json_encode(['status' => 'success', 'message' => 'Job created', 'job_id' => $job_id]);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Job creation failed']);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    }
    public function getAllJobs()
    {
        $jobs = $this->jobModel->getAllJobs();

        // Return jobs in JSON format
        echo json_encode($jobs);
    }
}
