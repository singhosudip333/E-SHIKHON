<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get instructor ID from URL
if (!isset($_GET['id'])) {
    header("Location: viewinstructor.php");
    exit();
}

$instructor_id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch instructor details
$sql = "SELECT * FROM instructor WHERE id = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: viewinstructor.php");
    exit();
}

$instructor = $result->fetch_assoc();

// Fetch all courses
$courses_sql = "SELECT * FROM courses ORDER BY created_at DESC";
$stmt = $conn->prepare($courses_sql);
$stmt->execute();
$courses_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($instructor['full_name']); ?> - E-SHIKHON</title>
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

        .profile-header {
            background-color: #f8f9fa;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 15px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1.5rem;
            border: 5px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #f0f2f5;
            border-radius: 15px 15px 0 0 !important;
        }

        .expertise-badge {
            background-color: #e8f4f8;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 1rem;
            color: #2980b9;
            display: inline-block;
            margin: 0.5rem;
        }

        .course-card {
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

        .social-links a {
            color: #3498db;
            margin: 0 10px;
            font-size: 1.2rem;
            transition: color 0.2s;
        }

        .social-links a:hover {
            color: #2980b9;
        }

        .contact-btn {
            background-color: #3498db;
            color: white;
            padding: 0.5rem 2rem;
            border-radius: 25px;
            border: none;
            transition: all 0.3s;
        }

        .contact-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
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
                        <li><a class="dropdown-item" href="changepass.html">Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Profile Header -->
    <div class="container">
        <div class="profile-header text-center">
            <img src="<?php echo !empty($instructor['profile_image']) ? 'uploads/instructor/' . htmlspecialchars($instructor['profile_image']) : 'assets/images/default_avatar.jpg'; ?>" 
                 alt="<?php echo htmlspecialchars($instructor['full_name']); ?>" 
                 class="profile-image">
            <h2 class="mb-2"><?php echo htmlspecialchars($instructor['full_name']); ?></h2>
            <p class="text-muted mb-3"><?php echo htmlspecialchars($instructor['field_expertise']); ?></p>
            <div class="social-links mb-3">
                <?php if ($instructor['portfolio_link']): ?>
                    <a href="<?php echo htmlspecialchars($instructor['portfolio_link']); ?>" target="_blank">
                        <i class="fas fa-globe"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <!-- About Section -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">About</h5>
                    </div>
                    <div class="card-body">
                        <p><?php echo nl2br(htmlspecialchars($instructor['bio'])); ?></p>
                        <hr>
                        <div class="mb-3">
                            <strong>Experience:</strong>
                            <p><?php echo htmlspecialchars($instructor['experience_years']); ?> years</p>
                        </div>
                        <div class="mb-3">
                            <strong>Expertise:</strong><br>
                            <span class="expertise-badge">
                                <?php echo htmlspecialchars($instructor['field_expertise']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses Section -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">All Available Courses</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            if ($courses_result->num_rows > 0) {
                                while ($course = $courses_result->fetch_assoc()) {
                            ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card course-card">
                                        <img src="<?php echo htmlspecialchars($course['course_image'] ?? 'assets/images/default_course.jpg'); ?>" 
                                             class="course-image" 
                                             alt="<?php echo htmlspecialchars($course['title']); ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                            <p class="card-text text-muted">
                                                <?php echo substr(htmlspecialchars($course['description']), 0, 100) . '...'; ?>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($course['level']); ?></span>
                                                <a href="course_details.php?id=<?php echo $course['id']; ?>" 
                                                   class="btn btn-outline-primary btn-sm">View Course</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            } else {
                            ?>
                                <div class="col-12 text-center">
                                    <p>No courses available at the moment.</p>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
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