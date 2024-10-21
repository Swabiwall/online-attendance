<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the input data
$data = json_decode(file_get_contents("php://input"), true);

// Check if the data is properly received
if (!isset($data['student_id']) || !isset($data['status'])) {
    echo "Invalid input data.";
    http_response_code(400); // Bad request
    exit;
}

$student_id = $data['student_id'];
$status = $data['status'];

// Check if the student ID exists in the students table
$student_check_sql = "SELECT id, student_name, father_name FROM students WHERE id = ?";
$stmt = $conn->prepare($student_check_sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Invalid student ID.";
    http_response_code(400); // Bad request
    exit;
}

// Fetch the student data
$student = $result->fetch_assoc();
$student_name = $student['student_name'];
$father_name = $student['father_name'];

// Insert the attendance record with student_name and father_name
$sql = "INSERT INTO attendance (student_id, student_name, father_name, status) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $student_id, $student_name, $father_name, $status);

if ($stmt->execute()) {
    // If attendance is successfully inserted, show the student's details
    echo "Attendance marked successfully for Student ID: " . $student_id . 
         " (" . $student_name . "), Father's Name: " . $father_name . 
         ", Status: " . $status;
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
