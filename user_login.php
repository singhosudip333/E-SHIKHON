<?php
session_start();
include("connection.php"); // Include your database connection file

$error = ''; // Variable to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate form inputs
    if (empty($email) || empty($password)) {
        $error = 'Both fields are required.';
    } else {
        // Sanitize email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // SQL query to fetch user data
        $sql = "SELECT * FROM `users` WHERE `email` = ? AND `password` = ? AND `status` = 'active'";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        
        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows == 1) {
            // Start session and set user session variable
            $_SESSION['email'] = $email;

            // Redirect to the dashboard or another page
            header("Location: loguser.php");
            exit();
        } else {
            $error = 'Invalid email or password, or your account may be banned.';
        }

        // Close the statement
        $stmt->close();
    }
    
    // Close the database connection
    $conn->close();
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Login</title>
    <style>
        .header {
            width: 100%;
            background-color: #3370ad; /* Light grey background */
            padding: 10px 0;
            text-align: center;
            border-bottom: 1px solid #acb7c2; /* Border at the bottom */
        }
        .header h1 {
            margin: 0;
            font-size: 2rem; /* Adjust font size as needed */
            color: #f3f5f7; /* Dark grey text color */
        }

        .message {
            margin: 20px;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <header class="header">
        <h1>E-SHIKHON</h1>
    </header>
    <br><br>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="message error"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form  method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">
                                    Please provide a valid email address.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback">
                                    Please provide your password.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-2">Don't have an account? <a href="user_signup.php">Sign up here</a></p><br>
                        <a href="index.html" class="btn btn-secondary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
