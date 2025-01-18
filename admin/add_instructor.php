<?php
session_start();
include 'backend/connection.php'; // Include your database connection

// Check if admin is logged in and get their username
if (isset($_SESSION['username'])) {
    $admin_username = $_SESSION['username'];
} else {
    // If the admin is not logged in, redirect them to the login page
    header("Location: index.php");
    exit;
}

// Handle instructor approval or rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accept'])) {
        $apply_id = $_POST['apply_id'];
        $password = $_POST['password'];

        // Get instructor details from apply_instructor table
        $stmt = $conn->prepare("SELECT * FROM apply_instructor WHERE id = ?");
        $stmt->bind_param("i", $apply_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $instructor = $result->fetch_assoc();

        // Check if instructor data was found
        if ($instructor) {
            // Insert data into the instructor table
            $stmt = $conn->prepare("INSERT INTO instructor (email, full_name, password, profile_image, phone, field_expertise, experience_years, portfolio_link, status, created_at) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())");
            $stmt->bind_param("ssssssss", 
                $instructor['email'],
                $instructor['fullname'],
                $password,
                $instructor['image'],
                $instructor['phonenumber'],
                $instructor['expertise_field'],
                $instructor['experience'],
                $instructor['portfolio_link']
            );
            
            if($stmt->execute()) {
                // Update the status in the apply_instructor table
                $stmt = $conn->prepare("UPDATE apply_instructor SET status = 'approved' WHERE id = ?");
                $stmt->bind_param("i", $apply_id);
                $stmt->execute();
                
                // TODO: Send email to instructor with their credentials
            }
        }
    } elseif (isset($_POST['reject'])) {
        $apply_id = $_POST['apply_id'];
        $stmt = $conn->prepare("UPDATE apply_instructor SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $apply_id);
        $stmt->execute();
    }
}

// Fetch all pending instructor applications
$query = "SELECT * FROM apply_instructor WHERE status = 'pending'";
$result = $conn->query($query);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instructor Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        body {
            background-color: #f8f9fa;
        }

        h2 {
            color: #343a40;
            margin-bottom: 30px;
        }

        .table {
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: bold;
        }

        .table-hover tbody tr:hover {
            background-color: #e9ecef;
        }

        .table img {
            max-width: 50px;
            height: auto;
            border-radius: 50%;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .btn {
            font-weight: bold;
            text-transform: uppercase;
        }

        .modal-content {
            border-radius: 10px;
        }

        .highlight-row {
            background-color: #d1ecf1;
        }

        #search {
            border: 2px solid #007bff;
            border-radius: 20px;
            padding: 10px 20px;
            outline: none;
            transition: all 0.3s ease;
        }

        #search:focus {
            border-color: #0056b3;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <!-- Admin Dashboard Button -->
    <div class="text-end mb-3">
        <a href="admin_dashboard.php" class="btn btn-primary">Go to Dashboard</a>
    </div>

    <h2 class="text-center">Pending Instructor Applications</h2>
    <input type="text" id="search" class="form-control mb-3" placeholder="Search by email">
    
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Profile Image</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Field of Expertise</th>
                <th>Experience</th>
                <th>Portfolio</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="instructorTable">
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php if ($row['image']): ?>
                        <img src="../uploads/instructors/<?php echo $row['image']; ?>" alt="Instructor Image">
                    <?php else: ?>
                        <img src="../uploads/instructors/default.jpg" alt="Default Image">
                    <?php endif; ?>
                </td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phonenumber']; ?></td>
                <td><?php echo $row['expertise_field']; ?></td>
                <td><?php echo $row['experience']; ?></td>
                <td>
                    <?php if ($row['portfolio_link']): ?>
                        <a href="<?php echo $row['portfolio_link']; ?>" target="_blank" class="btn btn-sm btn-info">View</a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#passwordModal-<?php echo $row['id']; ?>">
                        Accept
                    </button>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="apply_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="reject" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this application?')">
                            Reject
                        </button>
                    </form>

                    <!-- Password Modal -->
                    <div class="modal fade" id="passwordModal-<?php echo $row['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Set Instructor Password</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="post">
                                    <div class="modal-body">
                                        <input type="hidden" name="apply_id" value="<?php echo $row['id']; ?>">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" name="password" required>
                                            <small class="text-muted">This password will be sent to the instructor's email.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="accept" class="btn btn-primary">Approve & Send Credentials</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        // Search functionality
        $('#search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#instructorTable tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Highlight row on hover
        $('tbody tr').hover(function() {
            $(this).addClass('highlight-row');
        }, function() {
            $(this).removeClass('highlight-row');
        });
    });
</script>
</body>
</html>
