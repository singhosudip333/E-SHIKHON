<?php
include("connection.php"); // Include your database connection file

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullname = $_POST['name'];
    $phone = $_POST['phone'];
    $occupation = $_POST['occupation'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $dob = $_POST['birthdate'];
    $password = $_POST['password'];
    $status = 'active'; // Default status

    // Validate form inputs
    if (empty($fullname) || empty($phone) || empty($occupation) || empty($email) || empty($address) || empty($dob) || empty($password)) {
        $error = 'All fields are required.';
    } else {
        // Sanitize email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        // SQL query to insert user data
        $sql = "INSERT INTO `users`(`email`, `fullname`, `phone`, `dob`, `password`, `occupation`, `address`, `status`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $email, $fullname, $phone, $dob, $password, $occupation, $address, $status);
        
        // Execute the query
        if ($stmt->execute()) {
            $success = 'Sign up successful! You can now log in.';
        } else {
            $error = 'Error: ' . $stmt->error;
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

    <title>Sign Up</title>
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

        .success {
            background-color: #d4edda;
            color: #155724;
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
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Sign Up</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($success): ?>
                            <div class="message success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="message error"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="signup.php" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">
                                    Please provide your full name.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                                <div class="invalid-feedback">
                                    Please provide a valid phone number.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="occupation" class="form-label">Occupation</label>
                                <input type="text" class="form-control" id="occupation" name="occupation" required>
                                <div class="invalid-feedback">
                                    Please provide your occupation.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">
                                    Please provide a valid email address.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                <div class="invalid-feedback">
                                    Please provide your address.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="birthdate" class="form-label">Birth Date</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                                <div class="invalid-feedback">
                                    Please provide your birth date.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback">
                                    Please provide a password.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0">Already have an account? <a href="user_login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><br>

    <!-- Optional JavaScript -->
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
