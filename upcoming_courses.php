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

// Fetch user's upcoming courses
$sql = "SELECT c.*, e.enrollment_date, e.progress, i.full_name as instructor_name, i.profile_image as instructor_image
        FROM courses c 
        JOIN enrollments e ON c.id = e.course_id 
        JOIN users u ON e.user_id = u.id 
        JOIN instructor i ON c.instructor_id = i.id
        WHERE u.email = '$email' 
        AND e.status = 'active'
        AND c.status = 'upcoming'
        ORDER BY e.enrollment_date DESC";

$result = mysqli_query($conn, $sql);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Courses - E-SHIKHON</title>
    
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

        .start-date {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="loguser.php">E-SHIKHON</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="loguser.php">Dashboard</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4">My Upcoming Courses</h2>
        
        <div class="row">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($course = mysqli_fetch_assoc($result)) {
            ?>
                <div class="col-md-4">
                    <div class="card course-card">
                        <img src="<?php echo htmlspecialchars($course['course_image'] ?? 'assets/images/default_course.jpg'); ?>" 
                             class="card-img-top course-image" 
                             alt="<?php echo htmlspecialchars($course['title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                            
                            <div class="instructor-info">
                                <img src="<?php echo htmlspecialchars($course['instructor_image'] ?? 'assets/images/default_avatar.jpg'); ?>" 
                                     class="instructor-avatar" 
                                     alt="Instructor">
                                <span><?php echo htmlspecialchars($course['instructor_name']); ?></span>
                            </div>
                            
                            <p class="card-text">
                                <small class="text-muted">Enrolled: <?php echo date('M d, Y', strtotime($course['enrollment_date'])); ?></small>
                            </p>
                            
                            <?php if (isset($course['start_date'])): ?>
                            <p class="start-date">
                                Starts on: <?php echo date('M d, Y', strtotime($course['start_date'])); ?>
                            </p>
                            <?php endif; ?>

                            <p class="card-text"><?php echo htmlspecialchars(substr($course['description'] ?? '', 0, 100)) . '...'; ?></p>
                            
                            <a href="course_details.php?id=<?php echo $course['id']; ?>" 
                               class="btn btn-primary w-100">View Details</a>
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
                        You don't have any upcoming courses at the moment.
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