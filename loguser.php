<!doctype html>
<?php
// Start the session
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get user email from session (assuming it's stored in session)
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

$email = $_SESSION['email'];

// Fetch user data
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found");
}

?>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <title>User Dashboard</title>
    <style>
        .navbar {
            margin-bottom: 20px;
        }
        .dropdown-menu {
            /* Ensures the dropdown menu aligns with the button */
            position: absolute;
            right: 0;
            left: auto;
            margin-top: .5rem;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Added box shadow */
        }
        .table thead th {
            vertical-align: bottom;
        }
        .btn-primary {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Added box shadow to buttons */
        }
        .footer {
            background-color: #000000;
            padding: 2rem 0;
            text-align: center;
            color: white;
        }
        .footer a {
            color: #fdfdfd;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .social-links a {
            margin: 0 0.5rem;
            color: #fafafa;
        }
        .social-links a:hover {
            color: #007bff;
        }
        
        /* Enhanced navbar styling */
        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            padding: 1rem 0;
        }
        
        .navbar-brand h1 {
            color: #2c3e50;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        /* Card enhancements */
        .card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Button styling */
        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        .btn-info {
            background-color: #5bc0de;
            border: none;
            color: white;
        }

        /* Welcome section enhancement */
        .welcome-section {
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .welcome-section h2 {
            color: #2c3e50;
            font-weight: 700;
        }

        /* Table styling */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #2c3e50;
            font-weight: 600;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Footer enhancement */
        .footer {
            background-color: #2c3e50;
            color: #ecf0f1;
        }

        .footer h5 {
            color: #3498db;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .social-links ul {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background-color: #3498db;
            transform: translateY(-3px);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="loguser.php"><h1>E-SHIKHON</h1></a>
            <div class="d-flex">
                <a class="btn btn-outline-primary me-2" href="viewinstructor.php">All Instructors</a>
                <a class="btn btn-outline-primary me-2" href="all_courses.php">All Courses</a>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo htmlspecialchars($user['fullname']); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="viewprofile.php">View Profile</a></li>
                        <li><a class="dropdown-item" href="editprofile.php">Update Profile</a></li>
                        <li><a class="dropdown-item" href="change-password.php">Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav><br>

    <div class="container">
        <div class="welcome-section">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2>Welcome, <?php echo htmlspecialchars($user['fullname']); ?>!</h2>
                    <p class="lead">Here's a summary of your current activities and progress.</p>
                    <div class="user-info mt-3">
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                        <p><strong>Occupation:</strong> <?php echo htmlspecialchars($user['occupation']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Overview -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ongoing Courses</h5>
                        <p class="card-text">View and manage your active courses.</p>
                        <a href="ongoing_courses.php" class="btn btn-primary">View Courses</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Upcoming Courses</h5>
                        <p class="card-text">Check out the courses you are enrolled in that will start soon.</p>
                        <a href="upcoming_courses.php" class="btn btn-primary">View Upcoming</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Previous Courses -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Previous Courses</h5>
                        <p class="card-text">Review your completed courses.</p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Course Name</th>
                                    <th scope="col">Completion Date</th>
                                    <th scope="col">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch completed courses
                                $completed_sql = "SELECT c.title, c.id, e.enrollment_date, e.last_accessed 
                                                FROM courses c 
                                                JOIN enrollments e ON c.id = e.course_id 
                                                JOIN users u ON e.user_id = u.id 
                                                WHERE u.email = '$email' 
                                                AND e.status = 'completed'
                                                ORDER BY e.last_accessed DESC";
                                
                                $completed_result = mysqli_query($conn, $completed_sql);
                                
                                if (mysqli_num_rows($completed_result) > 0) {
                                    while ($course = mysqli_fetch_assoc($completed_result)) {
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($course['title']); ?></td>
                                        <td><?php echo date('F d, Y', strtotime($course['last_accessed'])); ?></td>
                                        <td>
                                            <a href="course_details.php?id=<?php echo $course['id']; ?>" 
                                               class="btn btn-info btn-sm">View Details</a>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="3" class="text-center">No completed courses yet.</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile and Support -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Profile Management</h5>
                        <p class="card-text">Update your profile details and manage your account settings.</p>
                        <a href="viewprofile.php" class="btn btn-primary">Manage Profile</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Help & Support</h5>
                        <p class="card-text">Access the help center or contact support for assistance.</p>
                        <a href="#" class="btn btn-primary">Get Help</a>
                    </div>
                </div>
            </div>
        </div>
    </div><br>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Useful Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <address>
                        <p>Sylhet, Bnagladesh</p>
                        <p>Email: <a href="mailto:info@example.com">eshikhon@gmail.com</a></p>
                        <p>Phone: +0325164852</p>
                    </address>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <div class="social-links">
                        <ul class="list-unstyled">
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr>
            <div class="mt-4">
                <p>&copy; 2024 E-SHIKHON. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Optional JavaScript -->
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
