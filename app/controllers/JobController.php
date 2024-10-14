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
    public function deleteJob($job_id)
    {
        if ($this->jobModel->deleteJob($job_id)) {
            echo json_encode(['status' => 'success', 'message' => 'Job deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Job deletion failed']);
        }
    }
    public function updateJob($job_id)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        if (isset($data['title']) && isset($data['description']) && isset($data['experience']) && isset($data['education']) && isset($data['location'])) {
            $title = $data['title'];
            $description = $data['description'];
            $experience = $data['experience'];
            $education = $data['education'];
            $location = $data['location']; // Expecting an array of locations

            if ($this->jobModel->updateJob($job_id, $title, $description, $experience, $education, $location)) {
                echo json_encode(['status' => 'success', 'message' => 'Job updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Job update failed']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    }
    public function getJobById($job_id)
    {
        $job = $this->jobModel->getJobById($job_id);

        if ($job) {
            header('Content-Type: application/json');
            echo json_encode($job);
        } else {
            http_response_code(404); // Job not found
            echo json_encode(['status' => 'error', 'message' => 'Job not found']);
        }
    }
}
