<?php
include '../connection.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

$instructor_id = $_SESSION['id'];
$video_id = $_POST['video_id'];
$title = $_POST['title'];
$course_id = $_POST['course_id'];

// Verify ownership
$check_query = "SELECT cv.* FROM course_videos cv 
                JOIN courses c ON cv.course_id = c.id 
                WHERE cv.id = ? AND c.instructor_id = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("ii", $video_id, $instructor_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: edit_content.php");
    exit();
}

// Update video
$update_query = "UPDATE course_videos 
                SET title = ?, course_id = ? 
                WHERE id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("sii", $title, $course_id, $video_id);

if ($update_stmt->execute()) {
    header("Location: edit_content.php");
} else {
    echo "Error updating video: " . $conn->error;
}

$conn->close();
?> 