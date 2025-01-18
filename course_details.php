<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get course ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: all_courses.php");
    exit();
}

$course_id = $_GET['id'];

// Fetch course details with instructor information
$course_sql = "SELECT c.*, i.full_name as instructor_name, i.bio as instructor_bio 
               FROM courses c 
               LEFT JOIN instructor i ON c.instructor_id = i.id 
               WHERE c.id = ?";
$stmt = $conn->prepare($course_sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: all_courses.php");
    exit();
}

$course = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - E-SHIKHON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        
        .navbar-brand h1 {
            color: #2c3e50;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .course-header {
            background-color: #f8f9fa;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }

        .course-image {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .instructor-profile {
            display: flex;
            align-items: center;
            margin: 2rem 0;
        }

        .instructor-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 1rem;
            object-fit: cover;
        }

        .course-features {
            background-color: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .feature-item {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .feature-item i {
            margin-right: 1rem;
            color: #3498db;
        }

        .curriculum-section {
            margin-top: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="loguser.php"><h1>E-SHIKHON</h1></a>
            <div class="d-flex">
                <a class="btn btn-outline-primary me-2" href="viewinstructor.php">All Instructors</a>
                <a class="btn btn-outline-primary me-2" href="all_courses.php">All Courses</a>
                <?php if (isset($_SESSION['email'])): ?>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo htmlspecialchars($_SESSION['fullname'] ?? 'My Account'); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="viewprofile.php">View Profile</a></li>
                        <li><a class="dropdown-item" href="editprofile.php">Update Profile</a></li>
                        <li><a class="dropdown-item" href="change-password.php">Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Course Header -->
    <div class="course-header">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <?php if (!empty($course['course_image'])): ?>
                    <div class="mb-4">
                        <img src="uploads/courses/<?php echo htmlspecialchars($course['course_image']); ?>" 
                             alt="<?php echo htmlspecialchars($course['title']); ?>" 
                             class="course-image">
                    </div>
                    <?php endif; ?>
                    <h1><?php echo htmlspecialchars($course['title']); ?></h1>
                    <p class="lead"><?php echo htmlspecialchars($course['description']); ?></p>
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-primary me-2"><?php echo htmlspecialchars($course['level']); ?></span>
                        <span class="text-muted">
                            <i class="fas fa-users me-1"></i> 
                            <?php echo number_format($course['enrolled_students'] ?? 0); ?> students enrolled
                        </span>
                    </div>
                    <div class="instructor-profile">
                        <img src="assets/images/default_profile.jpg" 
                             alt="<?php echo htmlspecialchars($course['instructor_name']); ?>" 
                             class="instructor-image">
                        <div>
                            <h5 class="mb-0">Instructor: <?php echo htmlspecialchars($course['instructor_name']); ?></h5>
                            <p class="text-muted mb-0"><?php echo substr(htmlspecialchars($course['instructor_bio']), 0, 100) . '...'; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="course-features">
                        <h3 class="mb-4">Course Features</h3>
                        <div class="feature-item">
                            <i class="fas fa-clock"></i>
                            <span>Duration: <?php echo htmlspecialchars($course['duration']); ?></span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Level: <?php echo htmlspecialchars($course['level']); ?></span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-tag"></i>
                            <span>Price: ৳<?php echo number_format($course['price'], 2); ?></span>
                        </div>
                        <form action="process_enrollment.php" method="POST">
                            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                            <button type="submit" class="btn btn-primary w-100 mt-3">Enroll Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <!-- Course Description -->
                <section class="mb-5">
                    <h3 class="section-title">Course Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                </section>

                <!-- Course Materials -->
                <?php
                // Check if user is enrolled
                $check_enrollment_sql = "SELECT * FROM enrollments 
                                       WHERE course_id = ? 
                                       AND user_id = (SELECT id FROM users WHERE email = ?)
                                       AND status = 'active'";
                $check_stmt = $conn->prepare($check_enrollment_sql);
                $check_stmt->bind_param("is", $course_id, $_SESSION['email']);
                $check_stmt->execute();
                $is_enrolled = $check_stmt->get_result()->num_rows > 0;

                if ($is_enrolled):
                    // Fetch course materials
                    $materials_sql = "SELECT * FROM course_materials 
                                    WHERE course_id = ? 
                                    ORDER BY module_number, created_at";
                    $materials_stmt = $conn->prepare($materials_sql);
                    $materials_stmt->bind_param("i", $course_id);
                    $materials_stmt->execute();
                    $materials = $materials_stmt->get_result();
                ?>
                
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?> 