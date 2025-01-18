<?php
require 'connection.php'; // Include your database connection file

// Assuming the instructor ID is stored in the session
session_start();
$instructor_id = $_SESSION['id'];

// Fetch instructor details
$query = $conn->prepare("SELECT * FROM instructor WHERE id = ?");

$query->bind_param("i", $instructor_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $instructor = $result->fetch_assoc();
    echo json_encode($instructor);
} else {
    echo json_encode(['error' => 'Instructor not found']);
}
?>
