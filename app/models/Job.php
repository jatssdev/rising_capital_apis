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
}
