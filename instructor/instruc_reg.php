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
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $full_name = filter_var(trim($_POST['full_name']), FILTER_SANITIZE_STRING);
    $phone = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);
   // $bio = filter_var(trim($_POST['bio']), FILTER_SANITIZE_STRING);
   // $experience_years = filter_var(trim($_POST['experience_years']), FILTER_SANITIZE_NUMBER_INT);
    $portfolio_link = filter_var(trim($_POST['portfolio_link']), FILTER_SANITIZE_URL);
    $field_expertise = isset($_POST['field_expertise']) ? implode(',', $_POST['field_expertise']) : '';

    // Validate required fields
    if (empty($email) || empty($password) || empty($confirm_password) || empty($full_name)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Hash the password for secure storage
       // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email already exists in the database
        $stmt = $conn->prepare("SELECT id FROM instructor WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            // Insert data into the database
            $stmt = $conn->prepare("INSERT INTO instructor (email, password, full_name, phone, bio, field_expertise, experience_years, portfolio_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssis", $email, $password, $full_name, $phone, $bio, $field_expertise, $experience_years, $portfolio_link);

            if ($stmt->execute()) {
                $success = 'Registration successful!  You can now log in.';

            } else {
                $error = 'An error occurred during registration. Please try again.';
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
        .badge {
        background-color: #17a2b8; 
        color: white;
        margin: 0 5px 5px 0;
        font-size: 1rem;
        font-weight: 500;
    }
    .badge .ms-2 {
        color: white;
        font-weight: bold;
    }
    .badge:hover {
        background-color: #138f9e; 
    }

    .strength-bar {
            height: 5px;
            margin-top: 5px;
            transition: width 0.3s ease-in-out;
        }
        .strength-bar.weak { width: 33%; background-color: red; }
        .strength-bar.medium { width: 66%; background-color: orange; }
        .strength-bar.strong { width: 100%; background-color: green; }
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
                        <h4>Instructor Registration</h4>
                        <p class="warning">Warning: Use authentic information, false information can lead you to get banned.</p>

                    </div>
                    <div class="card-body">
                        <form  method="POST">
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
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required oninput="checkStrength()">
                                <div class="strength-bar" id="strength-bar"></div>
                            </div>


                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="field_expertise" class="form-label">Field of Expertise</label>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="expertiseDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Select Fields
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="expertiseDropdown">
                                        <li><a class="dropdown-item" href="#">Artificial Intelligence</a></li>
                                        <li><a class="dropdown-item" href="#">Web Development</a></li>
                                        <li><a class="dropdown-item" href="#">Data Science</a></li>
                                        <li><a class="dropdown-item" href="#">Machine Learning</a></li>
                                        <li><a class="dropdown-item" href="#">Cybersecurity</a></li>
                                        <li><a class="dropdown-item" href="#">Blockchain Technology</a></li>
                                        <li><a class="dropdown-item" href="#">Cloud Computing</a></li>
                                        <!-- Add more options here -->
                                    </ul>
                                </div>
                                <div id="selectedFields" class="mt-3">
                                    <!-- Selected fields will appear here as badges -->
                                </div>
                            <div class="mb-3">
                                <label for="experience" class="form-label">Years of Experience</label>
                                <input type="number" class="form-control" id="experience_years" name="experience_years">
                            </div>
                            <div class="mb-3">
                                <label for="portfolio_link" class="form-label">Portfolio Link</label>
                                <input type="url" class="form-control" id="portfolio_link" name="portfolio_link">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                            <?php if (!empty($error)): ?>
                                <div class="error-message"><?php echo $error; ?></div>
                            <?php elseif (!empty($success)): ?>
                                <div class="success-message"><?php echo $success; ?></div>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0">Already have an account? <a href="instruc_login.php" class="text-primary">Login here</a></p>
                        <br>
                        <a href="../index.html" class="btn btn-secondary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div><br>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        const selectedFields = document.getElementById('selectedFields');
        const fieldExpertiseInput = document.getElementById('field_expertise');
        const selectedOptions = new Set();

        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault();
                const expertise = event.target.textContent;

                if (!selectedOptions.has(expertise)) {
                    selectedOptions.add(expertise);
                    updateSelectedFields();
                }
            });
        });

        function updateSelectedFields() {
            selectedFields.innerHTML = '';
            selectedOptions.forEach(option => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-primary';
                badge.textContent = option;
                badge.style.cursor = 'pointer';

                const removeBtn = document.createElement('span');
                removeBtn.className = 'ms-2';
                removeBtn.innerHTML = '&times;';
                removeBtn.style.cursor = 'pointer';
                removeBtn.onclick = () => {
                    selectedOptions.delete(option);
                    updateSelectedFields();
                };

                badge.appendChild(removeBtn);
                selectedFields.appendChild(badge);
            });

            fieldExpertiseInput.value = Array.from(selectedOptions).join(', ');
        }



        function checkStrength() {
            var strengthBar = document.getElementById('strength-bar');
            var password = document.getElementById('password').value;
            var strength = 0;

            if (password === "") {
        strengthBar.className = 'strength-bar';  // Reset class to hide the bar
        return;
    }

            if (password.length >= 6) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            if (strength < 2) {
                strengthBar.className = 'strength-bar weak';
            } else if (strength == 2) {
                strengthBar.className = 'strength-bar medium';
            } else if (strength > 2) {
                strengthBar.className = 'strength-bar strong';
            }
        }
    </script>
</body>
</html>
