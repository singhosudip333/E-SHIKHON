<?php
include '../connection.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

$instructor_id = $_SESSION['id'];
$video_id = $_GET['id'];

// Fetch video details
$video_query = "SELECT cv.*, c.title as course_title 
                FROM course_videos cv 
                JOIN courses c ON cv.course_id = c.id 
                WHERE cv.id = ? AND c.instructor_id = ?";
$video_stmt = $conn->prepare($video_query);
$video_stmt->bind_param("ii", $video_id, $instructor_id);
$video_stmt->execute();
$video = $video_stmt->get_result()->fetch_assoc();

if (!$video) {
    header("Location: edit_content.php");
    exit();
}

// Fetch available courses for dropdown
$courses_query = "SELECT id, title FROM courses WHERE instructor_id = ?";
$courses_stmt = $conn->prepare($courses_query);
$courses_stmt->bind_param("i", $instructor_id);
$courses_stmt->execute();
$courses = $courses_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Video - E-SHIKHON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Video</h4>
                        <a href="edit_content.php" class="btn btn-secondary">Back</a>
                    </div>
                    <div class="card-body">
                        <form action="process_edit_video.php" method="POST">
                            <input type="hidden" name="video_id" value="<?php echo $video_id; ?>">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Video Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($video['title']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="course_id" class="form-label">Course</label>
                                <select class="form-select" id="course_id" name="course_id" required>
                                    <?php while ($course = $courses->fetch_assoc()): ?>
                                        <option value="<?php echo $course['id']; ?>" <?php echo ($course['id'] == $video['course_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($course['title']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Video</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 