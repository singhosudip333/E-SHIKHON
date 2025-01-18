<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Check if course ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ongoing_courses.php");
    exit();
}

$course_id = $_GET['id'];
$email = $_SESSION['email'];

// Get user ID and check enrollment
$enrollment_sql = "SELECT e.*, u.id as user_id 
                  FROM enrollments e
                  JOIN users u ON e.user_id = u.id
                  WHERE u.email = ? AND e.course_id = ? AND e.status = 'active'";
$enrollment_stmt = $conn->prepare($enrollment_sql);
$enrollment_stmt->bind_param("si", $email, $course_id);
$enrollment_stmt->execute();
$enrollment = $enrollment_stmt->get_result()->fetch_assoc();

if (!$enrollment) {
    header("Location: ongoing_courses.php");
    exit();
}

// Fetch course details
$course_sql = "SELECT c.*, i.full_name as instructor_name, i.profile_image as instructor_image 
               FROM courses c 
               JOIN instructor i ON c.instructor_id = i.id 
               WHERE c.id = ?";
$course_stmt = $conn->prepare($course_sql);
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course = $course_stmt->get_result()->fetch_assoc();

// Fetch course materials
$materials_sql = "SELECT * FROM course_materials 
                 WHERE course_id = ? 
                 ORDER BY module_number, created_at";
$materials_stmt = $conn->prepare($materials_sql);
$materials_stmt->bind_param("i", $course_id);
$materials_stmt->execute();
$materials = $materials_stmt->get_result();

// Fetch course videos
$videos_sql = "SELECT * FROM course_videos 
               WHERE course_id = ? 
               ORDER BY module_number, upload_date";
$videos_stmt = $conn->prepare($videos_sql);
$videos_stmt->bind_param("i", $course_id);
$videos_stmt->execute();
$videos = $videos_stmt->get_result();

// Debug: Print video data
echo "<!-- Debug: Video Data -->";
while ($video = $videos->fetch_assoc()) {
    echo "<!-- Video Path: " . htmlspecialchars($video['video_path']) . " -->";
}
// Reset result set pointer
mysqli_data_seek($videos, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Learning Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .course-header {
            background-color: #f8f9fa;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .progress {
            height: 10px;
            margin: 1rem 0;
        }
        
        .nav-pills .nav-link {
            color: #495057;
        }
        
        .nav-pills .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        
        .material-card {
            margin-bottom: 1rem;
            transition: transform 0.2s;
        }
        
        .material-card:hover {
            transform: translateY(-2px);
        }
        
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="loguser.php"><h1>E-SHIKHON</h1></a>
            <div class="d-flex">
                <a href="ongoing_courses.php" class="btn btn-outline-primary me-2">My Courses</a>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo htmlspecialchars($_SESSION['fullname'] ?? 'My Account'); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="viewprofile.php">View Profile</a></li>
                        <li><a class="dropdown-item" href="editprofile.php">Update Profile</a></li>
                        <li><a class="dropdown-item" href="changepass.html">Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Course Header -->
    <div class="course-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><?php echo htmlspecialchars($course['title']); ?></h1>
                    <p class="lead mb-0">Instructor: <?php echo htmlspecialchars($course['instructor_name']); ?></p>
                </div>
                <div class="col-md-4">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" 
                             style="width: <?php echo htmlspecialchars($enrollment['progress']); ?>%" 
                             aria-valuenow="<?php echo htmlspecialchars($enrollment['progress']); ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?php echo htmlspecialchars($enrollment['progress']); ?>%
                        </div>
                    </div>
                    <small class="text-muted">Your Progress</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <!-- Course Navigation -->
                <div class="nav flex-column nav-pills me-3" id="courseTab" role="tablist">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#overview" type="button">Overview</button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#materials" type="button">Course Materials</button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#videos" type="button">Video Lectures</button>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="tab-content" id="courseTabContent">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="overview">
                        <h3>Course Overview</h3>
                        <p><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                        
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title">Course Information</h5>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-clock me-2"></i> Duration: <?php echo htmlspecialchars($course['duration']); ?></li>
                                    <li><i class="fas fa-graduation-cap me-2"></i> Level: <?php echo htmlspecialchars($course['level']); ?></li>
                                    <li><i class="fas fa-calendar me-2"></i> Enrolled: <?php echo date('M d, Y', strtotime($enrollment['enrollment_date'])); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Materials Tab -->
                    <div class="tab-pane fade" id="materials">
                        <h3>Course Materials</h3>
                        <?php if ($materials->num_rows > 0): ?>
                            <?php 
                            $current_module = null;
                            while ($material = $materials->fetch_assoc()):
                                if ($current_module !== $material['module_number']):
                                    if ($current_module !== null) echo '</div>'; // Close previous module div
                                    $current_module = $material['module_number'];
                            ?>
                                <h4 class="mt-4">Module <?php echo htmlspecialchars($material['module_number']); ?></h4>
                                <div class="module-materials">
                            <?php endif; ?>
                                    <div class="card material-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($material['title']); ?></h5>
                                                    <p class="card-text text-muted"><?php echo htmlspecialchars($material['description']); ?></p>
                                                </div>
                                                <a href="download_material.php?id=<?php echo $material['id']; ?>" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-download me-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                            <?php endwhile; ?>
                            </div> <!-- Close last module div -->
                        <?php else: ?>
                            <div class="alert alert-info">
                                No materials have been uploaded for this course yet.
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Videos Tab -->
                    <div class="tab-pane fade" id="videos">
                        <h3>Video Lectures</h3>
                        <?php if ($videos->num_rows > 0): ?>
                            <?php 
                            $current_module = null;
                            while ($video = $videos->fetch_assoc()):
                                if ($current_module !== $video['module_number']):
                                    if ($current_module !== null) echo '</div>'; // Close previous module div
                                    $current_module = $video['module_number'];
                            ?>
                                <h4 class="mt-4">Module <?php echo htmlspecialchars($video['module_number']); ?></h4>
                                <div class="module-videos">
                            <?php endif; ?>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($video['title']); ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars($video['description']); ?></p>
                                            <div class="video-container">
                                                <iframe src="instructor/uploads/videos/<?php echo htmlspecialchars($video['video_path']); ?>" 
                                                        frameborder="0" 
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                        allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>
                            <?php endwhile; ?>
                            </div> <!-- Close last module div -->
                        <?php else: ?>
                            <div class="alert alert-info">
                                No video lectures have been uploaded for this course yet.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize Bootstrap tabs
        document.addEventListener('DOMContentLoaded', function() {
            var triggerTabList = [].slice.call(document.querySelectorAll('#courseTab button'))
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl)
                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            })
        });
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?> 