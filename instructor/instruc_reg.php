<?php
// Include database connection file
include('connection.php');

// Initialize variables for error and success messages
$error = '';
$success = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize it
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $full_name = filter_var(trim($_POST['full_name']), FILTER_SANITIZE_STRING);
    $phone = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);
    $expertise_field = filter_var(trim($_POST['expertise_field']), FILTER_SANITIZE_STRING);
    $experience = filter_var(trim($_POST['experience_years']), FILTER_SANITIZE_NUMBER_INT);
    $portfolio_link = filter_var(trim($_POST['portfolio_link']), FILTER_SANITIZE_URL);

    // Validate required fields
    if (empty($email) || empty($full_name) || empty($expertise_field)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Check if the email already exists in the database
        $stmt = $conn->prepare("SELECT id FROM apply_instructor WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'An application with this email already exists.';
        } else {
            // Insert data into the database
            $stmt = $conn->prepare("INSERT INTO apply_instructor (email, fullname, phonenumber, expertise_field, experience, portfolio_link, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())");
            $stmt->bind_param("ssssss", $email, $full_name, $phone, $expertise_field, $experience, $portfolio_link);

            if ($stmt->execute()) {
                $success = 'Application submitted successfully! Please wait for admin approval.';
            } else {
                $error = 'An error occurred during submission. Please try again. Error: ' . $stmt->error;
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Instructor Registration</title>
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
        .btn-primary {
            background-color: #3370ad;
            border-color: #3370ad;
        }
        .btn-primary:hover {
            background-color: #285a8c;
            border-color: #285a8c;
        }
        .error-message, .success-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }
        .success-message {
            color: green;
        }
        .card-header h4 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
        .warning {
            color: #d9534f;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>E-SHIKHON</h1>
    </header><br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Instructor Application Form</h4>
                        <p class="warning">Warning: Use authentic information, false information can lead to application rejection.</p>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="mb-3">
                                <label for="field_expertise" class="form-label">Field of Expertise</label>
                                <select class="form-select" id="field_expertise" name="expertise_field" required>
                                    <option value="">Select Field</option>
                                    <option value="Artificial Intelligence">Artificial Intelligence</option>
                                    <option value="Web Development">Web Development</option>
                                    <option value="Data Science">Data Science</option>
                                    <option value="Machine Learning">Machine Learning</option>
                                    <option value="Cybersecurity">Cybersecurity</option>
                                    <option value="Blockchain Technology">Blockchain Technology</option>
                                    <option value="Cloud Computing">Cloud Computing</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="experience" class="form-label">Years of Experience</label>
                                <input type="number" class="form-control" id="experience_years" name="experience_years">
                            </div>
                            <div class="mb-3">
                                <label for="portfolio_link" class="form-label">Portfolio Link</label>
                                <input type="url" class="form-control" id="portfolio_link" name="portfolio_link">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit Application</button>
                            <?php if (!empty($error)): ?>
                                <div class="error-message"><?php echo $error; ?></div>
                            <?php elseif (!empty($success)): ?>
                                <div class="success-message"><?php echo $success; ?></div>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="../index.html" class="btn btn-secondary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div><br>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
