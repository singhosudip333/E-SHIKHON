<?php
include '../connection.php';
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: ../instruc_login.php');
    exit();
}

$instructor_id = $_SESSION['id'];
$response = ['success' => false];

// Validate required fields
if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['duration']) || 
    !isset($_POST['price']) || !isset($_POST['category']) || !isset($_POST['level'])) {
    $response['error'] = 'Missing required fields';
    echo json_encode($response);
    exit;
}

// Handle course image upload
$course_image = null;
if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($_FILES['course_image']['type'], $allowed_types)) {
        $response['error'] = 'Invalid image format. Only JPG, JPEG and PNG are supported.';
        echo json_encode($response);
        exit;
    }

    $upload_path = '../../uploads/courses/';
    if (!file_exists($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    $file_extension = pathinfo($_FILES['course_image']['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $upload_path . $new_filename;

    if (!move_uploaded_file($_FILES['course_image']['tmp_name'], $target_file)) {
        $response['error'] = 'Failed to upload course image';
        echo json_encode($response);
        exit;
    }
    $course_image = $new_filename;
}

// Insert course
$insert_query = "INSERT INTO courses (
    instructor_id, title, description, duration, 
    price, category, level, course_image, status, 
    created_at, start_date
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'draft', NOW(), ?)";

$stmt = $conn->prepare($insert_query);
$stmt->bind_param("isssdssss",
    $instructor_id,
    $_POST['title'],
    $_POST['description'],
    $_POST['duration'],
    $_POST['price'],
    $_POST['category'],
    $_POST['level'],
    $course_image,
    $_POST['start_date']
);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['course_id'] = $conn->insert_id;
    $response['message'] = 'Course created successfully';
} else {
    $response['error'] = 'Failed to create course';
}

echo json_encode($response); 