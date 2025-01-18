<?php
include '../connection.php';
session_start();

// Get JSON data
$json = file_get_contents('php://input');
$data = json_decode($json);

if (!isset($data->courseId)) {
    echo json_encode(['success' => false, 'error' => 'Course ID not provided']);
    exit;
}

$instructor_id = $_SESSION['id'];
$course_id = $data->courseId;

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

// Begin transaction
$conn->begin_transaction();

try {
    // Delete related records first (assuming there are foreign key constraints)
    // Delete feedback
    $delete_feedback = "DELETE FROM feedback WHERE course_id = ?";
    $feedback_stmt = $conn->prepare($delete_feedback);
    $feedback_stmt->bind_param("i", $course_id);
    $feedback_stmt->execute();
    
    // Delete course materials (if you have such a table)
    $delete_materials = "DELETE FROM course_materials WHERE course_id = ?";
    $materials_stmt = $conn->prepare($delete_materials);
    $materials_stmt->bind_param("i", $course_id);
    $materials_stmt->execute();
    
    // Finally delete the course
    $delete_course = "DELETE FROM courses WHERE id = ? AND instructor_id = ?";
    $course_stmt = $conn->prepare($delete_course);
    $course_stmt->bind_param("ii", $course_id, $instructor_id);
    $course_stmt->execute();
    
    // Commit transaction
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => 'Failed to delete course']);
}
?> 