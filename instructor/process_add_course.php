<?php
include '../connection.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$instructor_id = $_SESSION['id'];
$response = ['success' => false];

try {
    // Validate required fields
    $required_fields = ['title', 'description', 'category', 'price', 'duration', 'level', 'start_date'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Validate and process image upload
    if (!isset($_FILES['course_image']) || $_FILES['course_image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Course image is required');
    }

    $image = $_FILES['course_image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($image['type'], $allowed_types)) {
        throw new Exception('Invalid image type. Only JPG, PNG and GIF are allowed');
    }

    // Generate unique filename
    $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
    $filename = uniqid('course_') . '.' . $extension;
    $upload_path = '../../uploads/courses/' . $filename;

    // Create directory if it doesn't exist
    if (!file_exists('../../uploads/courses/')) {
        mkdir('../../uploads/courses/', 0777, true);
    }

    // Move uploaded file
    if (!move_uploaded_file($image['tmp_name'], $upload_path)) {
        throw new Exception('Failed to upload image');
    }

    // Prepare and execute SQL statement
    $query = "INSERT INTO courses (instructor_id, title, description, duration, price, category, level, created_at, status, start_date, course_image) 
              VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'pending', ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "issiissss",
        $instructor_id,
        $_POST['title'],
        $_POST['description'],
        $_POST['duration'],
        $_POST['price'],
        $_POST['category'],
        $_POST['level'],
        $_POST['start_date'],
        $filename
    );

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Course created successfully';
    } else {
        throw new Exception('Failed to create course: ' . $stmt->error);
    }

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response); 