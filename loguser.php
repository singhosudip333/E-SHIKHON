<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <title>User Dashboard</title>
    <style>
        .navbar {
            margin-bottom: 20px;
        }
        .dropdown-menu {
            /* Ensures the dropdown menu aligns with the button */
            position: absolute;
            right: 0;
            left: auto;
            margin-top: .5rem;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Added box shadow */
        }
        .table thead th {
            vertical-align: bottom;
        }
        .btn-primary {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Added box shadow to buttons */
        }
        .footer {
            background-color: #000000;
            padding: 2rem 0;
            text-align: center;
            color: white;
        }
        .footer a {
            color: #fdfdfd;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .social-links a {
            margin: 0 0.5rem;
            color: #fafafa;
        }
        .social-links a:hover {
            color: #007bff;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html"><h1>E-SHIKHON</h1></a>
            <div class="d-flex">
                <a class="btn btn-outline-primary me-2" href="viewinstructor.html">All Instructors</a>
                <a class="btn btn-outline-primary me-2" href="courselist.html">All Courses</a>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Nusrat
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">View Profile</a></li>
                        <li><a class="dropdown-item" href="#">Update Profile</a></li>
                        <li><a class="dropdown-item" href="changepass.html">Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav><br>

    <div class="container">
        <!-- Dashboard Overview -->
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>Welcome, Nusrat!</h2>
                <p>Here's a summary of your current activities and progress.</p>
            </div>
        </div><br>

        <!-- Course Overview -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ongoing Courses</h5>
                        <p class="card-text">View and manage your active courses.</p>
                        <a href="#" class="btn btn-primary">View Courses</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Upcoming Courses</h5>
                        <p class="card-text">Check out the courses you are enrolled in that will start soon.</p>
                        <a href="#" class="btn btn-primary">View Upcoming</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Previous Courses -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Previous Courses</h5>
                        <p class="card-text">Review your completed courses.</p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Course Name</th>
                                    <th scope="col">Completion Date</th>
                                    <th scope="col">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example row -->
                                <tr>
                                    <td>Course 1</td>
                                    <td>January 15, 2024</td>
                                    <td><a href="#" class="btn btn-info btn-sm">View Details</a></td>
                                </tr>
                                <tr>
                                    <td>Course 2</td>
                                    <td>February 20, 2024</td>
                                    <td><a href="#" class="btn btn-info btn-sm">View Details</a></td>
                                </tr>
                                <tr>
                                    <td>Course 3</td>
                                    <td>March 5, 2024</td>
                                    <td><a href="#" class="btn btn-info btn-sm">View Details</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile and Support -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Profile Management</h5>
                        <p class="card-text">Update your profile details and manage your account settings.</p>
                        <a href="#" class="btn btn-primary">Manage Profile</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Help & Support</h5>
                        <p class="card-text">Access the help center or contact support for assistance.</p>
                        <a href="#" class="btn btn-primary">Get Help</a>
                    </div>
                </div>
            </div>
        </div>
    </div><br>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Useful Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <address>
                        <p>Sylhet, Bnagladesh</p>
                        <p>Email: <a href="mailto:info@example.com">eshikhon@gmail.com</a></p>
                        <p>Phone: +0325164852</p>
                    </address>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <div class="social-links">
                        <ul class="list-unstyled">
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr>
            <div class="mt-4">
                <p>&copy; 2024 E-SHIKHON. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Optional JavaScript -->
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
