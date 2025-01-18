<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Check if material ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid material selection.";
    header("Location: all_courses.php");
    exit();
}

$material_id = $_GET['id'];

// Get user ID
$user_sql = "SELECT id FROM users WHERE email = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("s", $_SESSION['email']);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: login.php");
    exit();
}

// Check if user is enrolled in the course that contains this material
$access_sql = "SELECT m.*, c.title as course_title 
               FROM course_materials m
               JOIN courses c ON m.course_id = c.id
               JOIN enrollments e ON c.id = e.course_id
               WHERE m.id = ? 
               AND e.user_id = ?
               AND e.status = 'active'";
$access_stmt = $conn->prepare($access_sql);
$access_stmt->bind_param("ii", $material_id, $user['id']);
$access_stmt->execute();
$material = $access_stmt->get_result()->fetch_assoc();

if (!$material) {
    $_SESSION['error'] = "You don't have access to this material.";
    header("Location: all_courses.php");
    exit();
}

// Get the file path
$file_path = "uploads/materials/" . $material['file_name'];

// Check if file exists
if (!file_exists($file_path)) {
    $_SESSION['error'] = "Material file not found.";
    header("Location: course_details.php?id=" . $material['course_id']);
    exit();
}

// Set appropriate headers for file download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($material['file_name']) . '"');
header('Content-Length: ' . filesize($file_path));

// Output file contents
readfile($file_path);
exit();
?> 