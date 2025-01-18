<!DOCTYPE html>
<?php
// Start the session
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get user email from session
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch user's ongoing courses
$sql = "SELECT c.id, c.title as course_name, c.course_image, c.description,
        e.enrollment_date, e.progress,
        i.full_name as instructor_name, i.profile_image as instructor_image
        FROM courses c 
        JOIN enrollments e ON c.id = e.course_id 
        JOIN users u ON e.user_id = u.id 
        JOIN instructor i ON c.instructor_id = i.id
        WHERE u.email = '$email' 
        AND e.status = 'active'
        ORDER BY e.enrollment_date DESC";

$result = mysqli_query($conn, $sql);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ongoing Courses - E-SHIKHON</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        .course-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
        }
        
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        
        .course-image {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        
        .instructor-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .instructor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="loguser.php"><h1>E-SHIKHON</h1></a>
            <div class="d-flex">
                <a class="btn btn-outline-primary me-2" href="viewinstructor.html">All Instructors</a>
                <a class="btn btn-outline-primary me-2" href="all_courses.php">All Courses</a>
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

    <div class="container my-5">
        <h2 class="mb-4">My Ongoing Courses</h2>
        
        <div class="row">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($course = mysqli_fetch_assoc($result)) {
            ?>
                <div class="col-md-4">
                    <div class="card course-card">
                        <img src="<?php echo !empty($course['course_image']) ? 'uploads/courses/' . htmlspecialchars($course['course_image']) : 'images/default_course.jpg'; ?>" 
                             class="card-img-top course-image" 
                             alt="<?php echo htmlspecialchars($course['course_name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h5>
                            
                            <div class="instructor-info">
                                <img src="<?php echo !empty($course['instructor_image']) ? 'instructor/uploads/' . htmlspecialchars($course['instructor_image']) : 'images/default_avatar.jpg'; ?>" 
                                     class="instructor-avatar" 
                                     alt="Instructor">
                                <span><?php echo htmlspecialchars($course['instructor_name']); ?></span>
                            </div>
                            
                            <div class="progress mb-3">
                                <div class="progress-bar" 
                                     role="progressbar" 
                                     style="width: <?php echo htmlspecialchars($course['progress']); ?>%" 
                                     aria-valuenow="<?php echo htmlspecialchars($course['progress']); ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <?php echo htmlspecialchars($course['progress']); ?>%
                                </div>
                            </div>
                            
                            <p class="card-text">
                                <small class="text-muted">Enrolled: <?php echo date('M d, Y', strtotime($course['enrollment_date'])); ?></small>
                            </p>
                            
                            <a href="view_enrolled_course.php?id=<?php echo $course['id']; ?>" 
                               class="btn btn-primary w-100">Continue Learning</a>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
            ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        You don't have any ongoing courses at the moment.
                        <br>
                        <a href="all_courses.php" class="btn btn-primary mt-3">Browse Courses</a>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 