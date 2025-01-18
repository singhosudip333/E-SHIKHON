<?php
include 'connection.php';
session_start();

$instructor_id = $_SESSION['id'];

// Fetch recent videos for this instructor's courses
$query = "SELECT v.*, c.title as course_title 
          FROM course_videos v 
          JOIN courses c ON v.course_id = c.id 
          WHERE c.instructor_id = ? 
          ORDER BY v.upload_date DESC 
          LIMIT 10";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result = $stmt->get_result();

$videos = [];
while($video = $result->fetch_assoc()) {
    $videos[] = [
        'title' => htmlspecialchars($video['title']),
        'description' => htmlspecialchars($video['description']),
        'course_title' => htmlspecialchars($video['course_title']),
        'module_number' => $video['module_number'],
        'upload_date' => date('F j, Y', strtotime($video['upload_date']))
    ];
}

echo json_encode($videos);
?> 