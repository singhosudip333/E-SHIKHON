<?php
include 'connection.php';
session_start();

$instructor_id = $_SESSION['id'];

// Build query based on whether a course filter is applied
$query = "SELECT m.*, c.title as course_title 
          FROM course_materials m 
          JOIN courses c ON m.course_id = c.id 
          WHERE c.instructor_id = ?";
$params = [$instructor_id];
$types = "i";

if(isset($_GET['course_id']) && !empty($_GET['course_id'])) {
    $query .= " AND m.course_id = ?";
    $params[] = $_GET['course_id'];
    $types .= "i";
}

$query .= " ORDER BY m.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$materials = [];
while($material = $result->fetch_assoc()) {
    $materials[] = [
        'id' => $material['id'],
        'title' => htmlspecialchars($material['title']),
        'description' => htmlspecialchars($material['description']),
        'course_title' => htmlspecialchars($material['course_title']),
        'material_type' => $material['material_type'],
        'module_number' => $material['module_number'],
        'upload_date' => date('F j, Y', strtotime($material['created_at']))
    ];
}

echo json_encode($materials);
?> 