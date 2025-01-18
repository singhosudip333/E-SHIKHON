<?php
session_start();
include 'includes/config.php';

// Check if instructor ID is provided in URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$instructor_id = $_GET['id'];

// Fetch instructor details
$sql = "SELECT * FROM instructor WHERE id = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$instructor = $result->fetch_assoc();
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <title>Instructor Profile - E-SHIKHON</title>
    <style>
        .header {
            width: 100%;
            background-color: #3370ad;
            padding: 10px 0;
            text-align: center;
            border-bottom: 1px solid #acb7c2;
        }

        .header h1 {
            margin: 0;
            font-size: 2rem;
            color: #f3f5f7;
        }

        .profile-photo {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 5px solid #f8f9fa;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .instructor-info {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        .info-label {
            font-weight: bold;
            color: #3370ad;
            margin-bottom: 5px;
        }

        .info-value {
            margin-bottom: 20px;
            color: #333;
        }

        .courses-section {
            margin-top: 30px;
        }
    </style>
</head>

<body class="bg-light">
    <header class="header">
        <h1>Instructor Profile - E-SHIKHON</h1>
    </header>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="instructor-info">
                    <div class="text-center">
                        <img src="<?php echo !empty($instructor['profile_image']) ? './instructor/uploads/' . htmlspecialchars($instructor['profile_image']) : './images/360_F_243123463_zTooub557xEWABDLk0jJklDyLSGl2jrr.jpg'; ?>" 
                             alt="Profile Photo" class="profile-photo">
                        <h2 class="mb-4"><?php echo htmlspecialchars($instructor['full_name']); ?></h2>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="info-label">Field of Expertise</div>
                                <div class="info-value"><?php echo htmlspecialchars($instructor['field_expertise']); ?></div>
                            </div>

                            <div class="mb-4">
                                <div class="info-label">Experience</div>
                                <div class="info-value"><?php echo htmlspecialchars($instructor['experience_years']); ?> Years</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="info-label">Email</div>
                                <div class="info-value"><?php echo htmlspecialchars($instructor['email']); ?></div>
                            </div>

                            <?php if (!empty($instructor['portfolio_link'])): ?>
                            <div class="mb-4">
                                <div class="info-label">Portfolio</div>
                                <div class="info-value">
                                    <a href="<?php echo htmlspecialchars($instructor['portfolio_link']); ?>" target="_blank">
                                        View Portfolio
                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="info-label">About</div>
                        <div class="info-value"><?php echo nl2br(htmlspecialchars($instructor['bio'])); ?></div>
                    </div>

                    <!-- Courses Section -->
                    <div class="courses-section">
                        <h3 class="mb-4">Courses by <?php echo htmlspecialchars($instructor['full_name']); ?></h3>
                        <?php
                        // Fetch instructor's courses
                        $sql = "SELECT * FROM courses WHERE instructor_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $instructor_id);
                        $stmt->execute();
                        $courses = $stmt->get_result();
                        
                        if ($courses->num_rows > 0): ?>
                            <div class="row">
                                <?php while($course = $courses->fetch_assoc()): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card">
                                            <img src="<?php echo !empty($course['course_image']) ? 'uploads/courses/' . htmlspecialchars($course['course_image']) : 'images/default-course.jpg'; ?>" 
                                                 class="card-img-top" alt="Course Image">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                                <p class="card-text"><?php echo substr(htmlspecialchars($course['description']), 0, 100) . '...'; ?></p>
                                                <a href="view_course.php?id=<?php echo $course['id']; ?>" class="btn btn-primary">View Course</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p>No courses available from this instructor yet.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="viewinstructor.php" class="btn btn-secondary">Back to All Instructors</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html> 