<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if course_id is provided
if (!isset($_POST['course_id']) || !is_numeric($_POST['course_id'])) {
    $_SESSION['error'] = "Invalid course selection.";
    header("Location: all_courses.php");
    exit();
}

$course_id = $_POST['course_id'];

// Get user ID from email
$user_sql = "SELECT id FROM users WHERE email = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("s", $_SESSION['email']);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

if (!$user) {
    $_SESSION['error'] = "Please log in to enroll in courses.";
    header("Location: user_login.php");
    exit();
}

$user_id = $user['id'];

// Check if already enrolled
$check_sql = "SELECT * FROM enrollments WHERE course_id = ? AND user_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $course_id, $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['error'] = "You are already enrolled in this course.";
    header("Location: course_details.php?id=" . $course_id);
    exit();
}

// Get course details for price check and instructor id
$course_sql = "SELECT price, instructor_id FROM courses WHERE id = ?";
$course_stmt = $conn->prepare($course_sql);
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course_result = $course_stmt->get_result();
$course = $course_result->fetch_assoc();

if (!$course) {
    $_SESSION['error'] = "Course not found.";
    header("Location: all_courses.php");
    exit();
}

// Insert enrollment with all required fields
$enroll_sql = "INSERT INTO enrollments (user_id, instructor_id, course_id, enrollment_date, status, progress, last_accessed) VALUES (?, ?, ?, NOW(), 'active', 0, NOW())";
$enroll_stmt = $conn->prepare($enroll_sql);
$enroll_stmt->bind_param("iii", $user_id, $course['instructor_id'], $course_id);

if ($enroll_stmt->execute()) {
    $_SESSION['success'] = "Successfully enrolled in the course!";
} else {
    $_SESSION['error'] = "Error enrolling in the course. Please try again.";
}

mysqli_close($conn);
header("Location: course_details.php?id=" . $course_id);
exit();
?> 