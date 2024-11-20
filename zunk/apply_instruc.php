<?php
// Include your database connection
include("connection.php");

$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullname = $_POST['name'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phone'];
    $expertise_field = $_POST['field'];
    $relevent_exper = $_POST['expertise'];
    $experience = $_POST['expertise'];
    $portfolio_link = $_POST['linkedin'];

    // Handle file upload
    $image = $_FILES['photo']['name'];
    $imageTmp = $_FILES['photo']['tmp_name'];
    $imagePath = '../uploads/instructors/' . basename($image);

    // Move the uploaded file to the designated directory
    if (move_uploaded_file($imageTmp, $imagePath)) {
        // Prepare the SQL query to insert data
        $sql = "INSERT INTO `apply_instructor` (`fullname`, `image`, `email`, `phonenumber`, `expertise_field`, `relevent_exper`, `experience`, `portfolio_link`, `status`, `applied_at`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $fullname, $imagePath, $email, $phonenumber, $expertise_field, $relevent_exper, $experience, $portfolio_link);
        
        // Execute the query
        if ($stmt->execute()) {
         
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error uploading image.";
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

    <title>Apply to be an Instructor</title>
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
        .message.success {
            color: #28a745;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <header class="header">
        <h1>E-SHIKHON</h1>
    </header><br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Apply to be an Instructor</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($success): ?>
                            <div class="message success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        <p class="warning">Warning: Your application will be reviewed by the admin. Only qualified candidates will be contacted.</p>
                        <form  method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label for="field" class="form-label">Field of Expertise</label>
                                <input type="text" class="form-control" id="field" name="field" required>
                            </div>
                            <div class="form-group">
                                <label for="expertise" class="form-label">Relevant Expertise</label>
                                <input type="text" class="form-control" id="expertise" name="expertise" required>
                            </div>
                            <div class="form-group">
                                <label for="experience" class="form-label">Experience</label>
                                <input type="text" class="form-control" id="experience" name="experience" required>
                            </div>
                            <div class="form-group">
                                <label for="linkedin" class="form-label">LinkedIn or GitHub Profile</label>
                                <input type="url" class="form-control" id="linkedin" name="linkedin" placeholder="Optional">
                            </div>
                            <div class="form-group">
                                <label for="photo" class="form-label">Upload Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit Application</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0">Thank you for your interest in joining E-SHIKHON as an instructor.</p>
                    </div>
                </div>
            </div>
        </div>
    </div><br><br>

    <!-- Optional JavaScript -->
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
