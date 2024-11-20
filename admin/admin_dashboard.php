<?php
include("backend/connection.php");
session_start(); // Ensure session is started

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}
$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Reset basic styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .welcome {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .user-dropdown button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .user-dropdown button:hover {
            background-color: #0056b3;
        }

        .user-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f1f1f1;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
        }

        .user-dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s ease;
        }

        .user-dropdown-content a:hover {
            background-color: #ddd;
        }

        .user-dropdown:hover .user-dropdown-content {
            display: block;
        }

        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1000px;
            width: 100%;
        }

        .card {
            background: linear-gradient(135deg, #f9f9f9, #e6e6e6);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .card::before {
            content: '';
            position: absolute;
            top: -75px;
            left: -75px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .card:hover::before {
            transform: scale(1.5) rotate(45deg);
        }

        .card h3 {
            margin-bottom: 15px;
            font-size: 22px;
            color: #333;
            font-weight: 600;
        }

        .card p {
            margin-bottom: 20px;
            color: #666;
            font-size: 14px;
        }

        .btn {
            padding: 12px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            text-transform: uppercase;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        /* Add a soft background gradient to the page */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f0f0f0, #ffffff);
            z-index: -1;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="welcome">Welcome, <?php echo $username; ?>!</div>
        <div class="user-dropdown">
            <button><?php echo $username; ?> ▼</button>
            <div class="user-dropdown-content">
                <a href="change_pass.php">Change Password</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="card">
            <h3>Manage Users</h3>
            <p>View, edit, or delete users registered on the platform.</p>
            <button class="btn" onclick="window.location.href='manage_users.php'">Go to Manage Users</button>
        </div>
        <div class="card">
            <h3>Add Instructor</h3>
            <p>Add a new instructor after verification.</p>
            <button class="btn" onclick="window.location.href='add_instructor.php'">Go to Add Instructor</button>
        </div>
        <div class="card">
            <h3>Manage Instructors</h3>
            <p>View, edit, or delete instructors on the platform.</p>
            <button class="btn" onclick="window.location.href='manage_instructors.php'">Go to Manage Instructors</button>
        </div>
        <div class="card">
            <h3>Add Admins</h3>
            <p>Create new admin accounts to manage the platform.</p>
            <button class="btn" onclick="window.location.href='add_admin.php'">Go to Add Admins</button>
        </div>
    </div>

</body>
</html>
