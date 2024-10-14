<?php
// app/Models/Course.php

class Course
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    // Method to create a new course
    public function createCourse($name, $description, $duration, $instructor_details, $content, $status, $syllabus_url, $thumbnail_image)
    {
        $stmt = $this->mysqli->prepare(
            "INSERT INTO courses (name, description, duration, instructor_details, content, status, syllabus_url, thumbnail_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        // Convert arrays/objects to JSON format
        $description_json = json_encode($description);
        $duration_json = json_encode($duration);
        $instructor_details_json = json_encode($instructor_details);
        $content_json = json_encode($content);

        // Bind parameters to the query
        $stmt->bind_param(
            'ssssssss',
            $name,
            $description_json,
            $duration_json,
            $instructor_details_json,
            $content_json,
            $status,
            $syllabus_url,
            $thumbnail_image
        );

        // Execute the query and return the result
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function getAllCourses()
    {
        $query = "SELECT * FROM courses";
        $result = $this->mysqli->query($query);

        $courses = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Convert JSON fields back to their respective arrays/objects
                $row['description'] = json_decode($row['description'], true);
                $row['duration'] = json_decode($row['duration'], true);
                $row['instructor_details'] = json_decode($row['instructor_details'], true);
                $row['content'] = json_decode($row['content'], true);

                $courses[] = $row;
            }
        }

        return $courses;
    }
}
