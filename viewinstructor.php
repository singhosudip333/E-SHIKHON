<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all active instructors with their course count
$sql = "SELECT i.*, 
        (SELECT COUNT(*) FROM courses WHERE instructor_id = i.id) as total_courses
        FROM instructor i 
        WHERE i.status = 'active'
        ORDER BY total_courses DESC";

$result = mysqli_query($conn, $sql);
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <title>All Instructors - E-SHIKHON</title>
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

        .table th, .table td {
            vertical-align: middle;
        }

        .btn-link {
            text-decoration: none;
            color: #3498db;
            font-weight: 500;
        }

        .btn-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #f0f2f5;
            border-radius: 15px 15px 0 0 !important;
        }

        .instructor-rating {
            color: #f1c40f;
        }

        .field-expertise {
            background-color: #e8f4f8;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.9rem;
            color: #2980b9;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="loguser.php"><h1>E-SHIKHON</h1></a>
            <div class="d-flex">
                <a class="btn btn-outline-primary me-2 active" href="viewinstructor.php">All Instructors</a>
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
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>All Instructors</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Field of Expertise</th>
                                    <th scope="col">Experience</th>
                                    <th scope="col">Total Courses</th>
                                    <th scope="col">Contact</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    while ($instructor = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <td>
                                            <a href="view_instructor_profile.php?id=<?php echo $instructor['id']; ?>" class="btn-link">
                                                <?php echo htmlspecialchars($instructor['full_name']); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="field-expertise">
                                                <?php echo htmlspecialchars($instructor['field_expertise']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($instructor['experience_years']); ?> years</td>
                                        <td><?php echo htmlspecialchars($instructor['total_courses']); ?></td>
                                        <td>
                                            <a href="mailto:<?php echo htmlspecialchars($instructor['email']); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-envelope me-1"></i>Contact
                                            </a>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No instructors found.</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center">
                        <a href="loguser.php" class="btn btn-secondary">Back to Dashboard</a>
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