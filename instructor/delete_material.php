<?php
include 'connection.php';
session_start();

$instructor_id = $_SESSION['id'];
$response = ['success' => false];

// Get JSON data
$json = file_get_contents('php://input');
$data = json_decode($json);

if(!isset($data->material_id)) {
    $response['error'] = 'Material ID not provided';
    echo json_encode($response);
    exit;
}

// Verify material belongs to instructor's course
$verify_query = "SELECT m.*, c.instructor_id 
                FROM course_materials m 
                JOIN courses c ON m.course_id = c.id 
                WHERE m.id = ? AND c.instructor_id = ?";
$verify_stmt = $conn->prepare($verify_query);
$verify_stmt->bind_param("ii", $data->material_id, $instructor_id);
$verify_stmt->execute();
$result = $verify_stmt->get_result();

if($result->num_rows === 0) {
    $response['error'] = 'Material not found or access denied';
    echo json_encode($response);
    exit;
}

$material = $result->fetch_assoc();

// Begin transaction
$conn->begin_transaction();

try {
    // Delete material record
    $delete_query = "DELETE FROM course_materials WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $data->material_id);
    
    if(!$delete_stmt->execute()) {
        throw new Exception('Failed to delete material record');
    }

    // Delete file
    $file_path = 'uploads/materials/' . $material['file_path'];
    if(file_exists($file_path)) {
        unlink($file_path);
    }

    // Commit transaction
    $conn->commit();
    $response['success'] = true;

} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?> 