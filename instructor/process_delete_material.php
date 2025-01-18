<?php
include '../connection.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

$instructor_id = $_SESSION['id'];
$material_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verify ownership and get material details
$verify_query = "SELECT m.* FROM materials m 
                JOIN courses c ON m.course_id = c.id 
                WHERE m.id = ? AND c.instructor_id = ?";
$verify_stmt = $conn->prepare($verify_query);
$verify_stmt->bind_param("ii", $material_id, $instructor_id);
$verify_stmt->execute();
$result = $verify_stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: edit_content.php?error=unauthorized");
    exit();
}

$material = $result->fetch_assoc();

// Delete the material file if it exists
if (!empty($material['file_path']) && file_exists($material['file_path'])) {
    unlink($material['file_path']);
}

// Delete from database
$delete_query = "DELETE FROM materials WHERE id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $material_id);

if ($delete_stmt->execute()) {
    header("Location: edit_content.php?success=deleted");
} else {
    header("Location: edit_content.php?error=delete_failed");
}
exit(); 