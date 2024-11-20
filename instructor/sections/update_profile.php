<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($conn)) {
    include 'connection.php';
}

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

$instructor_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $full_name = $_POST['full_name'];
        $phone = $_POST['phone'];
        $bio = $_POST['bio'];
        $portfolio_link = $_POST['portfolio_link'];
        $field_expertise = $_POST['field_expertise'];

        // Debugging: Check POST and FILE data
        file_put_contents('debug.log', print_r($_POST, true), FILE_APPEND);
        file_put_contents('debug.log', print_r($_FILES, true), FILE_APPEND);

        $profile_image_path = null;

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
            $targetDir = "../uploads/";
            $fileName = time() . '_' . basename($_FILES['profile_image']['name']);
            $targetFilePath = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFilePath)) {
                $profile_image_path = $fileName;
            } else {
                throw new Exception("Failed to move uploaded file.");
            }
        }

        $sql = "UPDATE instructor SET 
                full_name = ?, 
                phone = ?, 
                bio = ?, 
                portfolio_link = ?, 
                field_expertise = ?";
        $params = [$full_name, $phone, $bio, $portfolio_link, $field_expertise];
        $types = "sssss";

        if ($profile_image_path) {
            $sql .= ", profile_image = ?";
            $params[] = $profile_image_path;
            $types .= "s";
        }

        $sql .= " WHERE id = ?";
        $params[] = $instructor_id;
        $types .= "i";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
        } else {
            throw new Exception("Execute failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error updating profile: ' . $e->getMessage()]);
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($conn)) $conn->close();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
