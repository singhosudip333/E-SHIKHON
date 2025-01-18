<?php
include 'connection.php';
session_start();

$instructor_id = $_SESSION['id'];
$response = ['success' => false];

// Validate required fields
if(!isset($_POST['course_id']) || !isset($_POST['title']) || !isset($_POST['module_number']) || 
   !isset($_POST['video_order']) || !isset($_FILES['video_file'])) {
    $response['error'] = 'Missing required fields';
    echo json_encode($response);
    exit;
}

// Validate file upload
if($_FILES['video_file']['error'] !== 0) {
    $response['error'] = 'Error uploading file';
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
$allowed_types = ['video/mp4', 'video/webm'];
if(!in_array($_FILES['video_file']['type'], $allowed_types)) {
    $response['error'] = 'Invalid video format. Only MP4 and WebM are supported.';
    echo json_encode($response);
    exit;
}

// Check file size (500MB max)
$max_size = 500 * 1024 * 1024; // 500MB in bytes
if($_FILES['video_file']['size'] > $max_size) {
    $response['error'] = 'File too large. Maximum size is 500MB.';
    echo json_encode($response);
    exit;
}

// Create upload directory if it doesn't exist
$upload_path = '../uploads/videos/';
if(!file_exists($upload_path)) {
    mkdir($upload_path, 0777, true);
}

// Generate unique filename
$file_extension = pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION);
$new_filename = uniqid() . '_' . time() . '.' . $file_extension;
$target_file = $upload_path . $new_filename;

// Begin transaction
$conn->begin_transaction();

try {
    // Move uploaded file
    if(!move_uploaded_file($_FILES['video_file']['tmp_name'], $target_file)) {
        throw new Exception('Failed to move uploaded file');
    }

    // Insert video record
    $insert_query = "INSERT INTO course_videos (
        course_id, title, description, video_path, 
        module_number, video_order, upload_date
    ) VALUES (?, ?, ?, ?, ?, ?, NOW())";

    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("isssii",
        $_POST['course_id'],
        $_POST['title'],
        $_POST['description'],
        $new_filename,
        $_POST['module_number'],
        $_POST['video_order']
    );

    if(!$insert_stmt->execute()) {
        throw new Exception('Failed to save video information');
    }

    // Commit transaction
    $conn->commit();
    $response['success'] = true;
    $response['video_id'] = $conn->insert_id;
    $response['message'] = 'Video uploaded successfully';

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