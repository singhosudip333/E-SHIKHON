<?php
include '../connection.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

$instructor_id = $_SESSION['id'];
$material_id = $_POST['material_id'];
$title = $_POST['title'];
$course_id = $_POST['course_id'];
$material_type = $_POST['material_type'];

// Verify ownership
$check_query = "SELECT m.* FROM course_materials m 
                JOIN courses c ON m.course_id = c.id 
                WHERE m.id = ? AND c.instructor_id = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("ii", $material_id, $instructor_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: edit_content.php");
    exit();
}

// Update material
$update_query = "UPDATE course_materials 
                SET title = ?, course_id = ?, material_type = ? 
                WHERE id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("sisi", $title, $course_id, $material_type, $material_id);

if ($update_stmt->execute()) {
    header("Location: edit_content.php");
} else {
    echo "Error updating material: " . $conn->error;
}

$conn->close();
?> 