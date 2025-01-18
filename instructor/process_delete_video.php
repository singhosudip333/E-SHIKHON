<?php
include '../connection.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

$instructor_id = $_SESSION['id'];
$video_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verify ownership and get video details
$verify_query = "SELECT v.* FROM videos v 
                JOIN courses c ON v.course_id = c.id 
                WHERE v.id = ? AND c.instructor_id = ?";
$verify_stmt = $conn->prepare($verify_query);
$verify_stmt->bind_param("ii", $video_id, $instructor_id);
$verify_stmt->execute();
$result = $verify_stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: edit_content.php?error=unauthorized");
    exit();
}

$video = $result->fetch_assoc();

// Delete the video file if it exists
if (!empty($video['file_path']) && file_exists($video['file_path'])) {
    unlink($video['file_path']);
}

// Delete from database
$delete_query = "DELETE FROM videos WHERE id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $video_id);

if ($delete_stmt->execute()) {
    header("Location: edit_content.php?success=deleted");
} else {
    header("Location: edit_content.php?error=delete_failed");
}
exit(); 