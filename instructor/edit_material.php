<?php
include '../connection.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

$instructor_id = $_SESSION['id'];
$material_id = $_GET['id'];

// Fetch material details
$material_query = "SELECT m.*, c.title as course_title 
                  FROM course_materials m 
                  JOIN courses c ON m.course_id = c.id 
                  WHERE m.id = ? AND c.instructor_id = ?";
$material_stmt = $conn->prepare($material_query);
$material_stmt->bind_param("ii", $material_id, $instructor_id);
$material_stmt->execute();
$material = $material_stmt->get_result()->fetch_assoc();

if (!$material) {
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
    <title>Edit Material - E-SHIKHON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Material</h4>
                        <a href="edit_content.php" class="btn btn-secondary">Back</a>
                    </div>
                    <div class="card-body">
                        <form action="process_edit_material.php" method="POST">
                            <input type="hidden" name="material_id" value="<?php echo $material_id; ?>">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Material Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($material['title']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="course_id" class="form-label">Course</label>
                                <select class="form-select" id="course_id" name="course_id" required>
                                    <?php while ($course = $courses->fetch_assoc()): ?>
                                        <option value="<?php echo $course['id']; ?>" <?php echo ($course['id'] == $material['course_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($course['title']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="material_type" class="form-label">Material Type</label>
                                <select class="form-select" id="material_type" name="material_type" required>
                                    <option value="document" <?php echo ($material['material_type'] == 'document') ? 'selected' : ''; ?>>Document</option>
                                    <option value="presentation" <?php echo ($material['material_type'] == 'presentation') ? 'selected' : ''; ?>>Presentation</option>
                                    <option value="worksheet" <?php echo ($material['material_type'] == 'worksheet') ? 'selected' : ''; ?>>Worksheet</option>
                                    <option value="assignment" <?php echo ($material['material_type'] == 'assignment') ? 'selected' : ''; ?>>Assignment</option>
                                    <option value="other" <?php echo ($material['material_type'] == 'other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Material</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 