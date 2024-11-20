<?php

include("connection.php"); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch the admin details
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        // Verify the password (assuming it's hashed in the database)
        if ($password === $admin['password']) {
            // Password is correct, start the session and redirect to the dashboard
            session_start();
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            
            header("Location: ../admin_dashboard.php");
            exit();
        } else {
            // Invalid password
            header("Location: ../index.php?error=Invalid Username or Password");
            exit();
        }
    } else {
        // Invalid username
        header("Location: ../index.php?error=Invalid Username or Password");
        exit();
    }
} else {
    // Redirect to login page if accessed directly
    header("Location: ../index.php");
    exit();
}

?>