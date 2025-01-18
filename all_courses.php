<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all courses
$courses_sql = "SELECT c.*, i.full_name as instructor_name 
                FROM courses c 
                LEFT JOIN instructor i ON c.instructor_id = i.id 
                ORDER BY c.created_at DESC";
$result = $conn->prepare($courses_sql);
$result->execute();
$courses_result = $result->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Courses - E-SHIKHON</title>
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

        .course-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
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

        .course-instructor {
            color: #3498db;
            font-weight: 500;
        }

        .course-level {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(52, 152, 219, 0.9);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .course-price {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.2rem;
        }

        .filters {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 15px;
            margin-bottom: 2rem;
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

    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col">
                <h2>All Available Courses</h2>
                <p class="text-muted">Explore our wide range of courses and start learning today</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <select class="form-select" id="levelFilter">
                        <option value="">All Levels</option>
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search courses...">
                </div>
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="row">
            <?php
            if ($courses_result->num_rows > 0) {
                while ($course = $courses_result->fetch_assoc()) {
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card course-card">
                        <div class="position-relative">
                            <img src="<?php echo !empty($course['course_image']) ? 'uploads/courses/' . htmlspecialchars($course['course_image']) : 'assets/images/default_course.jpg'; ?>" 
                                 class="course-image" 
                                 alt="<?php echo htmlspecialchars($course['title']); ?>">
                            <span class="course-level"><?php echo htmlspecialchars($course['level']); ?></span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="course-instructor mb-2">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                <?php echo htmlspecialchars($course['instructor_name']); ?>
                            </p>
                            <p class="card-text text-muted">
                                <?php echo substr(htmlspecialchars($course['description']), 0, 100) . '...'; ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="course-price">৳<?php echo number_format($course['price'], 2); ?></span>
                                <a href="course_details.php?id=<?php echo $course['id']; ?>" 
                                   class="btn btn-primary">View Details</a>
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

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Filter and Search functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const levelFilter = document.getElementById('levelFilter');
            const searchInput = document.getElementById('searchInput');
            const courseCards = document.querySelectorAll('.course-card');

            function filterCourses() {
                const selectedLevel = levelFilter.value.toLowerCase();
                const searchTerm = searchInput.value.toLowerCase();

                courseCards.forEach(card => {
                    const level = card.querySelector('.course-level').textContent.toLowerCase();
                    const title = card.querySelector('.card-title').textContent.toLowerCase();
                    const instructor = card.querySelector('.course-instructor').textContent.toLowerCase();
                    
                    const levelMatch = !selectedLevel || level === selectedLevel;
                    const searchMatch = !searchTerm || 
                                      title.includes(searchTerm) || 
                                      instructor.includes(searchTerm);

                    card.closest('.col-md-4').style.display = 
                        levelMatch && searchMatch ? 'block' : 'none';
                });
            }

            levelFilter.addEventListener('change', filterCourses);
            searchInput.addEventListener('input', filterCourses);
        });
    </script>
</body>
</html>
<?php
mysqli_close($conn);
?> 