<?php
include '../connection.php';
session_start();

$instructor_id = $_SESSION['id'];
$response = ['success' => false];

// Handle file upload
if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['profile_image']['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if(in_array($ext, $allowed)) {
        $new_filename = uniqid() . '.' . $ext;
        $upload_path = '../uploads/';
        
        // Create directory if it doesn't exist
        if(!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        
        if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path . $new_filename)) {
            // Update profile image in database
            $update_picture = "UPDATE instructor SET profile_image = ? WHERE id = ?";
            $picture_stmt = $conn->prepare($update_picture);
            $picture_stmt->bind_param("si", $new_filename, $instructor_id);
            $picture_stmt->execute();
        }
    }
}

// Update other profile information
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$field_expertise = $_POST['field_expertise'];
$bio = $_POST['bio'];
$experience_years = $_POST['experience_years'];
$portfolio_link = $_POST['portfolio_link'];

// Check if email is already used by another instructor
$email_check = "SELECT id FROM instructor WHERE email = ? AND id != ?";
$check_stmt = $conn->prepare($email_check);
$check_stmt->bind_param("si", $email, $instructor_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if($check_result->num_rows > 0) {
    $response['error'] = 'Email already in use by another instructor';
    echo json_encode($response);
    exit;
}

// Update profile
$update_query = "UPDATE instructor SET 
                full_name = ?, 
                email = ?, 
                phone = ?, 
                field_expertise = ?, 
                bio = ?,
                experience_years = ?,
                portfolio_link = ?
                WHERE id = ?";

$stmt = $conn->prepare($update_query);
$stmt->bind_param("sssssssi", 
    $full_name, 
    $email, 
    $phone, 
    $field_expertise, 
    $bio,
    $experience_years,
    $portfolio_link,
    $instructor_id
);

if($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['error'] = 'Failed to update profile';
}

echo json_encode($response);
?>
