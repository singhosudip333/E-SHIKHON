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
    <title>Upload Video - E-SHIKHON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .progress {
            height: 25px;
        }
        .progress-bar {
            font-size: 16px;
            line-height: 25px;
        }
    </style>
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
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Upload Course Video</h4>
                    </div>
                    <div class="card-body">
                        <form id="uploadVideoForm" enctype="multipart/form-data">
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
                                <label for="title" class="form-label">Video Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Video Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="video_file" class="form-label">Video File</label>
                                <input type="file" class="form-control" id="video_file" name="video_file" 
                                       accept="video/mp4,video/x-m4v,video/*" required>
                                <small class="text-muted">Maximum file size: 500MB. Supported formats: MP4, WebM</small>
                            </div>

                            <div class="mb-3">
                                <label for="module_number" class="form-label">Module Number</label>
                                <input type="number" class="form-control" id="module_number" name="module_number" 
                                       min="1" required>
                            </div>

                            <div class="mb-3">
                                <label for="video_order" class="form-label">Video Order in Module</label>
                                <input type="number" class="form-control" id="video_order" name="video_order" 
                                       min="1" required>
                            </div>

                            <div class="progress mb-3 d-none" id="uploadProgress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%">0%</div>
                            </div>

                            <button type="submit" class="btn btn-primary">Upload Video</button>
                        </form>
                    </div>
                </div>

                <!-- Recently Uploaded Videos -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Recently Uploaded Videos</h5>
                    </div>
                    <div class="card-body">
                        <div id="recentVideos">
                            <div class="alert alert-info">Loading recent videos...</div>
                            <!-- Videos will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load recent videos on page load
        loadRecentVideos();

        document.getElementById('uploadVideoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const progressBar = document.getElementById('uploadProgress');
            const progressBarInner = progressBar.querySelector('.progress-bar');
            
            progressBar.classList.remove('d-none');
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'process_video_upload.php', true);
            
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    progressBarInner.style.width = percentComplete + '%';
                    progressBarInner.textContent = Math.round(percentComplete) + '%';
                }
            };
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if(response.success) {
                        alert('Video uploaded successfully!');
                        document.getElementById('uploadVideoForm').reset();
                        progressBar.classList.add('d-none');
                        loadRecentVideos();
                    } else {
                        alert(response.error || 'Error uploading video');
                    }
                }
            };
            
            xhr.send(formData);
        });

        function loadRecentVideos() {
            fetch('get_recent_videos.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('recentVideos');
                    if(data.length === 0) {
                        container.innerHTML = '<div class="alert alert-info">No videos uploaded yet.</div>';
                        return;
                    }
                    
                    let html = '<div class="list-group">';
                    data.forEach(video => {
                        html += `
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">${video.title}</h6>
                                    <small class="text-muted">${video.upload_date}</small>
                                </div>
                                <p class="mb-1">${video.course_title} - Module ${video.module_number}</p>
                                <small class="text-muted">${video.description}</small>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                });
        }
    </script>
</body>
</html> 