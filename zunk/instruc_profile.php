<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>My Profile</title>
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

        .profile-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .btn-link {
            color: #007bff;
        }

        .btn-link:hover {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <header class="header">
        <h1>My Profile - E-SHIKHON</h1>
    </header>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <!-- Sidebar -->
                <div class="list-group">
                    <a href="./instruc_panel.html" class="list-group-item list-group-item-action " aria-current="true">Dashboard</a>
                    <a href="./indtruc_profile.html" class="list-group-item list-group-item-action active">My Profile</a>
                    <a href="./managecourse.html" class="list-group-item list-group-item-action">Manage Courses</a>
                    <a href="./viewrating.html" class="list-group-item list-group-item-action">View Ratings</a>
                    <a href="#" class="list-group-item list-group-item-action">Logout</a>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Main content -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Profile Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img src="./images/360_F_243123463_zTooub557xEWABDLk0jJklDyLSGl2jrr.jpg" alt="Profile Photo" class="profile-photo">
                        </div>
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" value="Dr. John Doe">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="johndoe@example.com">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" value="+1234567890">
                            </div>
                            <div class="mb-3">
                                <label for="field" class="form-label">Field</label>
                                <input type="text" class="form-control" id="field" value="Computer Science">
                            </div>
                            <div class="mb-3">
                                <label for="expertise" class="form-label">Expertise</label>
                                <input type="text" class="form-control" id="expertise" value="Artificial Intelligence">
                            </div>
                            <div class="mb-3">
                                <label for="linkedin" class="form-label">LinkedIn Profile</label>
                                <input type="url" class="form-control" id="linkedin" value="https://www.linkedin.com/in/johndoe">
                            </div>
                            <div class="mb-3">
                                <label for="github" class="form-label">GitHub Profile</label>
                                <input type="url" class="form-control" id="github" value="https://github.com/johndoe">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
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
