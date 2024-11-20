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
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $sql = "SELECT * FROM `instructor` WHERE `email` = ? ";
        
  
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        
        $stmt->execute();
        $result = $stmt->get_result();

      
        if ($result->num_rows == 1) {
           $user= $result->fetch_assoc();
           if ($user['status'] === 'banned' )
           {
            $error = 'Your account is blocked. Please contact support team.';
           }
           else {
         
            if ($user['password'] === $password) {           
                $_SESSION['full_name'] = $user['full_name']; // Assuming 'full_name' is a column in your database
                $_SESSION['id'] = $user['id']; // Assuming 'id' is a column in your database
                header("Location: in_dash.php");
                exit();
            } else {
                $error = 'Invalid password.';
            }
        }
           
        } else {
            $error = 'Invalid email or password, or your account may be banned.';
        }

        $stmt->close();
    }
    
    $conn->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Instructor Login</title>
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
        .container {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .warning {
            color: #d9534f;
            font-weight: bold;
            text-align: center;
        }
        .error-message {
            color: red; 
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>E-SHIKHON</h1>
    </header><br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Instructor Login</h4>
                    </div>
                    <div class="card-body">
                        <form action="instruc_login.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Instructor Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                            <?php if (!empty($error)): ?>
                                <div class="error-message"><?php echo $error; ?></div>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="warning">WARNING: Use the Email and password provided by the admin.</p>
                        <p class="mb-0">If you encounter any issues, please contact the Support team.</p>
                        <br>
                        <a href="../index.html" class="btn btn-secondary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div><BR></BR>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
