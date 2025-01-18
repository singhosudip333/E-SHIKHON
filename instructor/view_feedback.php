<?php
include '../connection.php';
session_start();
$instructor_id = $_SESSION['id'];

// Fetch all feedback for courses by this instructor
$query = "SELECT f.*, c.title as course_title, u.full_name as student_name 
          FROM feedback f 
          JOIN courses c ON f.course_id = c.id 
          JOIN users u ON f.user_id = u.id 
          WHERE c.instructor_id = ?
          ORDER BY f.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate average rating
$avg_query = "SELECT AVG(f.rating) as avg_rating 
              FROM feedback f 
              JOIN courses c ON f.course_id = c.id 
              WHERE c.instructor_id = ?";
$avg_stmt = $conn->prepare($avg_query);
$avg_stmt->bind_param("i", $instructor_id);
$avg_stmt->execute();
$avg_result = $avg_stmt->get_result();
$avg_rating = $avg_result->fetch_assoc()['avg_rating'];
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Course Feedback - E-SHIKHON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .star-rating {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Course Feedback and Ratings</h2>
        
        <!-- Overall Rating Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Overall Rating</h5>
                <div class="display-4 text-center">
                    <?php 
                    $rounded_rating = round($avg_rating, 1);
                    echo $rounded_rating;
                    ?>
                    <span class="star-rating">
                        <?php
                        for($i = 1; $i <= 5; $i++) {
                            if($i <= $rounded_rating) {
                                echo '<i class="bi bi-star-fill"></i>';
                            } elseif($i - 0.5 <= $rounded_rating) {
                                echo '<i class="bi bi-star-half"></i>';
                            } else {
                                echo '<i class="bi bi-star"></i>';
                            }
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Feedback List -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Student Feedback</h5>
                <?php if($result->num_rows > 0): ?>
                    <?php while($feedback = $result->fetch_assoc()): ?>
                        <div class="border-bottom mb-3 pb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6><?php echo htmlspecialchars($feedback['course_title']); ?></h6>
                                    <small class="text-muted">By <?php echo htmlspecialchars($feedback['student_name']); ?></small>
                                </div>
                                <div class="star-rating">
                                    <?php
                                    for($i = 1; $i <= 5; $i++) {
                                        if($i <= $feedback['rating']) {
                                            echo '<i class="bi bi-star-fill"></i>';
                                        } else {
                                            echo '<i class="bi bi-star"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <p class="mt-2 mb-0"><?php echo htmlspecialchars($feedback['comment']); ?></p>
                            <small class="text-muted">
                                <?php echo date('F j, Y', strtotime($feedback['created_at'])); ?>
                            </small>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        No feedback received yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 