<?php
// app/Models/Job.php

class Job
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    // Method to create a new job
    public function createJob($title, $description, $experience, $education, $location)
    {
        $stmt = $this->mysqli->prepare("INSERT INTO jobs (title, description, experience, education, location) VALUES (?, ?, ?, ?, ?)");

        // Convert location array to a JSON string
        $location_json = json_encode($location);

        $stmt->bind_param('sssss', $title, $description, $experience, $education, $location_json);

        if ($stmt->execute()) {
            return $this->mysqli->insert_id; // Return the ID of the newly created job
        } else {
            return false; // Insertion failed
        }
    }
    public function getAllJobs()
    {
        $query = "SELECT * FROM jobs ORDER BY created_at DESC"; // Fetch all jobs, most recent first
        $result = $this->mysqli->query($query);

        $jobs = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Decode the location field (which is stored as JSON in the database)
                $row['location'] = json_decode($row['location'], true);
                $jobs[] = $row;
            }
        }
        return $jobs;
    }
    public function deleteJob($job_id)
    {
        // First, delete all associated applications
        $stmt1 = $this->mysqli->prepare("DELETE FROM applications WHERE job_id = ?");
        $stmt1->bind_param('i', $job_id);
        $stmt1->execute();

        // Now delete the job
        $stmt2 = $this->mysqli->prepare("DELETE FROM jobs WHERE id = ?");
        $stmt2->bind_param('i', $job_id);

        if ($stmt2->execute()) {
            return true; // Deletion successful
        } else {
            return false; // Deletion failed
        }
    }
    public function updateJob($job_id, $title, $description, $experience, $education, $location)
    {
        $stmt = $this->mysqli->prepare(
            "UPDATE jobs SET title = ?, description = ?, experience = ?, education = ?, location = ? WHERE id = ?"
        );

        // Convert location array to a JSON string
        $location_json = json_encode($location);

        $stmt->bind_param('sssssi', $title, $description, $experience, $education, $location_json, $job_id);

        if ($stmt->execute()) {
            return true; // Update successful
        } else {
            return false; // Update failed
        }
    }
    public function getJobById($job_id)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM jobs WHERE id = ?");
        $stmt->bind_param('i', $job_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $job = $result->fetch_assoc();
                // Decode the location field from JSON to an array
                $job['location'] = json_decode($job['location'], true);
                return $job; // Return the job data
            }
        }

        return false; // Job not found or query failed
    }
}
