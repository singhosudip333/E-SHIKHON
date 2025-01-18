<?php
include '../connection.php';
session_start();

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Course ID not provided']);
    exit;
}

$course_id = $_GET['id'];
$instructor_id = $_SESSION['id'];

// Fetch course details ensuring it belongs to the current instructor
$query = "SELECT * FROM courses WHERE id = ? AND instructor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $course_id, $instructor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Course not found or access denied']);
    exit;
}

$course = $result->fetch_assoc();
echo json_encode($course);
?> 