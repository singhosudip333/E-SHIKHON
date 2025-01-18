<?php
// Include main configuration
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

// Check if instructor is logged in
if (!isset($_SESSION['id'])) {
    header('Location: ' . $base_url . '/index.php?page=login');
    exit();
}

// Get instructor information
$instructor_id = $_SESSION['id'];
$instructor_query = "SELECT * FROM instructor WHERE id = ? AND status = 'active'";
$stmt = $conn->prepare($instructor_query);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$instructor = $stmt->get_result()->fetch_assoc();

if (!$instructor) {
    session_destroy();
    header('Location: ' . $base_url . '/index.php?page=login');
    exit();
}

// Define instructor-specific paths using absolute paths
define('INSTRUCTOR_ROOT', dirname(__DIR__));
define('INSTRUCTOR_UPLOADS', $instructor_uploads);
define('VIDEO_UPLOADS', $video_uploads);
define('MATERIAL_UPLOADS', $material_uploads);

// Create upload directories if they don't exist
$directories = [INSTRUCTOR_UPLOADS, VIDEO_UPLOADS, MATERIAL_UPLOADS];
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
} 