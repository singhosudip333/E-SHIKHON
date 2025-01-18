<?php
include 'connection.php';
session_start();
$instructor_id = $_SESSION['id'];

// Fetch instructor's courses
$courses_query = "SELECT id, title FROM courses WHERE instructor_id = ? ORDER BY title";
$courses_stmt = $conn->prepare($courses_query);
$courses_stmt->bind_param("i", $instructor_id);
$courses_stmt->execute();
$courses_result = $courses_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Course Materials - E-SHIKHON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="mb-3">
            <a href="in_dash.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <!-- Upload Material Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Upload Course Material</h4>
                    </div>
                    <div class="card-body">
                        <form id="uploadMaterialForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="course" class="form-label">Select Course</label>
                                <select class="form-control" id="course" name="course_id" required>
                                    <option value="">Choose a course</option>
                                    <?php while($course = $courses_result->fetch_assoc()): ?>
                                        <option value="<?php echo $course['id']; ?>">
                                            <?php echo htmlspecialchars($course['title']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Material Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="material_type" class="form-label">Material Type</label>
                                <select class="form-control" id="material_type" name="material_type" required>
                                    <option value="document">Document</option>
                                    <option value="presentation">Presentation</option>
                                    <option value="worksheet">Worksheet</option>
                                    <option value="assignment">Assignment</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="material_file" class="form-label">Upload File</label>
                                <input type="file" class="form-control" id="material_file" name="material_file" required>
                                <small class="text-muted">Supported formats: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX</small>
            </div>

                            <div class="mb-3">
                                <label for="module_number" class="form-label">Module Number</label>
                                <input type="number" class="form-control" id="module_number" name="module_number" 
                                       min="1" required>
        </div>

                            <button type="submit" class="btn btn-primary">Upload Material</button>
                                    </form>
                    </div>
                </div>

                <!-- Materials List -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Course Materials</h4>
                        <select class="form-select" style="width: auto;" id="filterCourse">
                            <option value="">All Courses</option>
                            <?php 
                            $courses_stmt->execute();
                            $courses_result = $courses_stmt->get_result();
                            while($course = $courses_result->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $course['id']; ?>">
                                    <?php echo htmlspecialchars($course['title']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="card-body">
                        <div id="materialsList">
                            <!-- Materials will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this material?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
        </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load materials on page load and when filter changes
        document.addEventListener('DOMContentLoaded', loadMaterials);
        document.getElementById('filterCourse').addEventListener('change', loadMaterials);

        // Handle material upload
        document.getElementById('uploadMaterialForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('process_material_upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Material uploaded successfully!');
                    this.reset();
                    loadMaterials();
                } else {
                    alert(data.error || 'Error uploading material');
                }
            });
        });

        function loadMaterials() {
            const courseId = document.getElementById('filterCourse').value;
            fetch(`get_materials.php${courseId ? '?course_id=' + courseId : ''}`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('materialsList');
                    if(data.length === 0) {
                        container.innerHTML = '<div class="alert alert-info">No materials found.</div>';
                        return;
                    }
                    
                    let html = '<div class="list-group">';
                    data.forEach(material => {
                        html += `
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">${material.title}</h6>
                                        <p class="mb-1">${material.course_title} - Module ${material.module_number}</p>
                                        <small class="text-muted">${material.description}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="download_material.php?id=${material.id}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <button onclick="deleteMaterial(${material.id})" 
                                                class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                });
        }

        function deleteMaterial(materialId) {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
            
            document.getElementById('confirmDelete').onclick = function() {
                fetch('delete_material.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({material_id: materialId})
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        modal.hide();
                        loadMaterials();
                    } else {
                        alert(data.error || 'Error deleting material');
                    }
                });
            };
        }
    </script>
</body>
</html>