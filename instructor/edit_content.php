<?php
include '../connection.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

$instructor_id = $_SESSION['id'];

// Fetch videos
$video_query = "SELECT cv.*, c.title as course_title 
                FROM course_videos cv 
                JOIN courses c ON cv.course_id = c.id 
                WHERE c.instructor_id = ?
                ORDER BY cv.upload_date DESC";
$video_stmt = $conn->prepare($video_query);
$video_stmt->bind_param("i", $instructor_id);
$video_stmt->execute();
$videos = $video_stmt->get_result();

// Fetch materials
$material_query = "SELECT m.*, c.title as course_title 
                  FROM course_materials m 
                  JOIN courses c ON m.course_id = c.id 
                  WHERE c.instructor_id = ?
                  ORDER BY m.created_at DESC";
$material_stmt = $conn->prepare($material_query);
$material_stmt->bind_param("i", $instructor_id);
$material_stmt->execute();
$materials = $material_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Content - E-SHIKHON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Content</h2>
            <a href="./in_dash.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <!-- Videos Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Videos</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Course</th>
                                <th>Upload Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($video = $videos->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($video['title']); ?></td>
                                <td><?php echo htmlspecialchars($video['course_title']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($video['upload_date'])); ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editVideo(<?php echo $video['id']; ?>)">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteVideo(<?php echo $video['id']; ?>)">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Materials Section -->
        <div class="card">
            <div class="card-header">
                <h3>Materials</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Course</th>
                                <th>Type</th>
                                <th>Upload Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($material = $materials->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($material['title']); ?></td>
                                <td><?php echo htmlspecialchars($material['course_title']); ?></td>
                                <td><?php echo htmlspecialchars($material['material_type']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($material['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editMaterial(<?php echo $material['id']; ?>)">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteMaterial(<?php echo $material['id']; ?>)">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for handling actions -->
    <script>
        function editVideo(videoId) {
            if(confirm('Do you want to edit this video?')) {
                window.location.href = `edit_video.php?id=${videoId}`;
            }
        }

        function deleteVideo(videoId) {
            if(confirm('Are you sure you want to delete this video? This action cannot be undone.')) {
                window.location.href = `process_delete_video.php?id=${videoId}`;
            }
        }

        function editMaterial(materialId) {
            if(confirm('Do you want to edit this material?')) {
                window.location.href = `edit_material.php?id=${materialId}`;
            }
        }

        function deleteMaterial(materialId) {
            if(confirm('Are you sure you want to delete this material? This action cannot be undone.')) {
                window.location.href = `process_delete_material.php?id=${materialId}`;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 