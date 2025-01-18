<?php
include '../connection.php';
session_start();

$instructor_id = $_SESSION['id'];
$response = ['success' => false];

if(!isset($_POST['current_password']) || !isset($_POST['new_password'])) {
    $response['error'] = 'Missing required fields';
    echo json_encode($response);
    exit;
}

$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];

// Verify current password
$verify_query = "SELECT password FROM instructor WHERE id = ?";
$verify_stmt = $conn->prepare($verify_query);
$verify_stmt->bind_param("i", $instructor_id);
$verify_stmt->execute();
$result = $verify_stmt->get_result();
$instructor = $result->fetch_assoc();

if(!password_verify($current_password, $instructor['password'])) {
    $response['error'] = 'Current password is incorrect';
    echo json_encode($response);
    exit;
}

// Update password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$update_query = "UPDATE instructor SET password = ? WHERE id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("si", $hashed_password, $instructor_id);

if($update_stmt->execute()) {
    $response['success'] = true;
} else {
    $response['error'] = 'Failed to update password';
}

echo json_encode($response);
?> 