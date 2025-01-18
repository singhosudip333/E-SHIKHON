<?php
include '../connection.php';
session_start();
$instructor_id = $_SESSION['id'];

// Define default categories since we don't have a categories table yet
$default_categories = [
    ['id' => 'web-development', 'name' => 'Web Development'],
    ['id' => 'programming', 'name' => 'Programming'],
    ['id' => 'data-science', 'name' => 'Data Science'],
    ['id' => 'mobile-development', 'name' => 'Mobile Development'],
    ['id' => 'database', 'name' => 'Database'],
    ['id' => 'cybersecurity', 'name' => 'Cybersecurity'],
    ['id' => 'networking', 'name' => 'Networking'],
    ['id' => 'cloud-computing', 'name' => 'Cloud Computing'],
    ['id' => 'artificial-intelligence', 'name' => 'Artificial Intelligence'],
    ['id' => 'machine-learning', 'name' => 'Machine Learning']
];
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Course - E-SHIKHON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="mb-3">
            <a href="../in_dash.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Add New Course</h4>
                    </div>
                    <div class="card-body">
                        <form id="addCourseForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Course Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Course Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <?php foreach($default_categories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category['id']); ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price (in BDT)</label>
                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" required>
                            </div>

                            <div class="mb-3">
                                <label for="duration" class="form-label">Duration (in weeks)</label>
                                <input type="number" class="form-control" id="duration" name="duration" min="1" required>
                            </div>

                            <div class="mb-3">
                                <label for="level" class="form-label">Level</label>
                                <select class="form-control" id="level" name="level" required>
                                    <option value="">Select Level</option>
                                    <option value="Beginner">Beginner</option>
                                    <option value="Intermediate">Intermediate</option>
                                    <option value="Advanced">Advanced</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>

                            <div class="mb-3">
                                <label for="course_image" class="form-label">Course Image</label>
                                <input type="file" class="form-control" id="course_image" name="course_image" accept="image/*" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Create Course</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('addCourseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('process_add_course.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Course created successfully!');
                    window.location.href = 'edit_course.php';
                } else {
                    alert(data.error || 'Error creating course');
                }
            });
        });

        // Set minimum date to today for start_date
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('start_date').min = today;
    </script>
</body>
</html>
