<?php
include 'connection.php';
session_start();

$instructor_id = $_SESSION['id'];
$response = ['success' => false];

// Validate required fields
if(!isset($_POST['course_id']) || !isset($_POST['title']) || !isset($_POST['material_type']) || 
   !isset($_POST['module_number']) || !isset($_FILES['material_file'])) {
    $response['error'] = 'Missing required fields';
    echo json_encode($response);
    exit;
}

// Verify course belongs to instructor
$verify_query = "SELECT id FROM courses WHERE id = ? AND instructor_id = ?";
$verify_stmt = $conn->prepare($verify_query);
$verify_stmt->bind_param("ii", $_POST['course_id'], $instructor_id);
$verify_stmt->execute();
if($verify_stmt->get_result()->num_rows === 0) {
    $response['error'] = 'Invalid course selected';
    echo json_encode($response);
    exit;
}

// Check file type
$allowed_types = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
];

if(!in_array($_FILES['material_file']['type'], $allowed_types)) {
    $response['error'] = 'Invalid file format. Only PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX are supported.';
    echo json_encode($response);
    exit;
}

// Check file size (50MB max)
$max_size = 50 * 1024 * 1024; // 50MB in bytes
if($_FILES['material_file']['size'] > $max_size) {
    $response['error'] = 'File too large. Maximum size is 50MB.';
    echo json_encode($response);
    exit;
}

// Create upload directory if it doesn't exist
$upload_path = 'uploads/materials/';
if(!file_exists($upload_path)) {
    mkdir($upload_path, 0777, true);
}

// Generate unique filename
$file_extension = pathinfo($_FILES['material_file']['name'], PATHINFO_EXTENSION);
$new_filename = uniqid() . '_' . time() . '.' . $file_extension;
$target_file = $upload_path . $new_filename;

// Begin transaction
$conn->begin_transaction();

try {
    // Move uploaded file
    if(!move_uploaded_file($_FILES['material_file']['tmp_name'], $target_file)) {
        throw new Exception('Failed to move uploaded file');
    }

    // Insert material record
    $insert_query = "INSERT INTO course_materials (
        course_id, title, description, material_type,
        file_path, module_number, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, NOW())";

    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("issssi",
        $_POST['course_id'],
        $_POST['title'],
        $_POST['description'],
        $_POST['material_type'],
        $new_filename,
        $_POST['module_number']
    );

    if(!$insert_stmt->execute()) {
        throw new Exception('Failed to save material information');
    }

    // Commit transaction
    $conn->commit();
    $response['success'] = true;

} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    
    // Delete uploaded file if it exists
    if(file_exists($target_file)) {
        unlink($target_file);
    }
    
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?> 