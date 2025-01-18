<?php
include '../connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$instructor_id = $_SESSION['id'];
$course_id = $_POST['courseId'];
$title = $_POST['title'];
$description = $_POST['description'];
$price = $_POST['price'];

// Verify the course belongs to the instructor
$verify_query = "SELECT id FROM courses WHERE id = ? AND instructor_id = ?";
$verify_stmt = $conn->prepare($verify_query);
$verify_stmt->bind_param("ii", $course_id, $instructor_id);
$verify_stmt->execute();
$verify_result = $verify_stmt->get_result();

if ($verify_result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Course not found or access denied']);
    exit;
}

// Update the course
$update_query = "UPDATE courses SET title = ?, description = ?, price = ? WHERE id = ? AND instructor_id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("ssdii", $title, $description, $price, $course_id, $instructor_id);

if ($update_stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update course']);
}
?> 