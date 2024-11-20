<?php


?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Instructor Dashboard</title>
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

        .nav-link {
            color: #ffffff;
        }

        .card-title {
            font-weight: bold;
        }

        .btn-link {
            color: #007bff;
        }

        .btn-link:hover {
            text-decoration: none;
        }

        .no-underline a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <header class="header">
        <h1>Instructor Dashboard - E-SHIKHON</h1>
    </header>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <!-- Sidebar -->
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active" aria-current="true">Dashboard</a>
                    <a href="./indtruc_profile.php" class="list-group-item list-group-item-action">My Profile</a>
                    <a href="./managecourse.html" class="list-group-item list-group-item-action">Manage Courses</a>
                    <a href="./viewrating.html" class="list-group-item list-group-item-action">View Ratings</a>
                    <a href="#" class="list-group-item list-group-item-action">Logout</a>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Main content -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Welcome, Dr. John Doe</h4>
                    </div>
                    <div class="card-body">
                        <p>Here you can manage your courses, view ratings, and update your profile.</p>
                    </div>
                </div>

                <!-- Courses Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>My Courses</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped no-underline">
                            <thead>
                                <tr>
                                    <th scope="col">Course Name</th>
                                    <th scope="col">Topic</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="course-details.html" class="btn-link">Introduction to AI</a></td>
                                    <td>Artificial Intelligence</td>
                                    <td>10 weeks</td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="course-details.html" class="btn-link">Machine Learning Basics</a></td>
                                    <td>Machine Learning</td>
                                    <td>8 weeks</td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                                <!-- Add more courses as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Ratings Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Ratings & Feedback</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Average Rating:</strong> 4.7</p>
                        <p><strong>Recent Feedback:</strong> "Great course on AI, very informative!"</p>
                        <!-- Add more feedback as needed -->
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
