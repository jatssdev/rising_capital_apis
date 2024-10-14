<?php
// app/Controllers/CourseController.php

require_once 'app/Models/Course.php';

class CourseController
{
    private $courseModel;

    public function __construct()
    {
        global $mysqli;
        $this->courseModel = new Course($mysqli);
    }

    // Method to handle the POST request for creating a new course
    public function createCourse()
    {
        // Get the input from the request body
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        if (
            isset($data['name']) &&
            isset($data['description']) &&
            isset($data['duration']) &&
            isset($data['instructor_details']) &&
            isset($data['content']) &&
            isset($data['status'])
        ) {
            $name = $data['name'];
            $description = $data['description'];  // Should be an array
            $duration = $data['duration'];        // Should be an object with min and max keys
            $instructor_details = $data['instructor_details']; // Array of instructor details
            $content = $data['content'];          // Course content (modules and lessons)
            $status = $data['status'];
            $syllabus_url = isset($data['syllabus_url']) ? $data['syllabus_url'] : null;
            $thumbnail_image = isset($data['thumbnail_image']) ? $data['thumbnail_image'] : null;

            // Create the course
            if ($this->courseModel->createCourse($name, $description, $duration, $instructor_details, $content, $status, $syllabus_url, $thumbnail_image)) {
                echo json_encode(['status' => 'success', 'message' => 'Course created successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to create course']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    }
    public function getAllCourses()
    {
        $courses = $this->courseModel->getAllCourses();
        if ($courses) {
            echo json_encode($courses);  // Return all courses as JSON
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'No courses found']);
        }
    }
}
