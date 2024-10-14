<?php
// config/email.php

function sendApplicationEmail($application_id, $job_id, $name, $email, $phone)
{
    $to = 'j3tinr3val@gmail.com'; // Admin's email
    $subject = "New Job Application #$application_id for Job #$job_id";

    // Create an HTML email message
    $message = "
    <html>
    <head>
        <title>New Job Application</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <h2>New Job Application #$application_id</h2>
        <p>A new job application has been submitted with the following details:</p>
        <table>
            <tr>
                <th>Application ID</th>
                <td>$application_id</td>
            </tr>
            <tr>
                <th>Job ID</th>
                <td>$job_id</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>$name</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>$email</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>$phone</td>
            </tr>
        </table>
        <p>Please log in to your admin panel to view further details.</p>
    </body>
    </html>
    ";

    // Set headers for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@yourdomain.com" . "\r\n";
    // Send the email
    mail($to, $subject, $message, $headers);
}
