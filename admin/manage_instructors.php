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

// Handle banning or reactivating an instructor
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['ban'])) {
        $instructor_id = $_POST['instructor_id'];
        $stmt = $conn->prepare("UPDATE instructor SET status = 'banned' WHERE id = ?");
        $stmt->bind_param("i", $instructor_id);
        $stmt->execute();
    } elseif (isset($_POST['reactivate'])) {
        $instructor_id = $_POST['instructor_id'];
        $stmt = $conn->prepare("UPDATE instructor SET status = 'active' WHERE id = ?");
        $stmt->bind_param("i", $instructor_id);
        $stmt->execute();
    }
}

// Fetch all instructors
$query = "SELECT * FROM instructor";
$result = $conn->query($query);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Instructors</title>
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

        .dashboard-btn {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            margin-bottom: 20px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .dashboard-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container mt-5">
<div class="text-end mb-3">
        <a href="admin_dashboard.php" class="btn btn-primary">Go to Dashboard</a>
    </div>

    <h2 class="text-center">Manage Instructors</h2>
    <input type="text" id="search" class="form-control mb-3" placeholder="Search by email">

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Picture</th>
                <th>Email</th>
                <th>Field of Expertise</th>
                <th>Phone</th>
                <th>Portfolio</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="instructorTable">
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><img src="./uploads/<?php echo $row['profile_image']; ?>" alt="Instructor Image"></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['field_expertise']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><a href="<?php echo $row['portfolio_link']; ?>" target="_blank">View Portfolio</a></td>
                <td><?php echo ucfirst($row['status']); ?></td>
                <td>
                    <?php if ($row['status'] === 'banned'): ?>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="instructor_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="reactivate" class="btn btn-success">Reactivate</button>
                    </form>
                    <?php else: ?>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="instructor_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="ban" class="btn btn-danger">Ban</button>
                    </form>
                    <?php endif; ?>
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
