<?php
include 'connection.php';
session_start();
$instructor_id = $_SESSION['id'];

// Fetch instructor details
$query = "SELECT * FROM instructor WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result = $stmt->get_result();
$instructor = $result->fetch_assoc();
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instructor Dashboard - E-SHIKHON</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="in_dash.css" rel="stylesheet">
</head>
<body>
<button class="theme-toggle" id="themeToggle">
    <i class="bi bi-sun-fill" id="themeIcon"></i>
</button>

<header class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">E-SHIKHON</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav"> 
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">
                        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($instructor['full_name']); ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sign Out</a>
                </li>
            </ul>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4">
            <div class="container mt-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm" style="min-height: 200px;">
                            <div class="card-body">
                                <h5 class="card-title">Profile</h5>
                                <p class="card-text">View and edit your personal information.</p>
                                <a href="profile.php" class="btn btn-primary ">View Profile</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm" style="min-height: 200px;">
                            <div class="card-body">
                                <h5 class="card-title">Add Course</h5>
                                <p class="card-text">Create and publish new courses for students.</p>
                                <a href="./add_course.php" class="btn btn-primary">Add New Course</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm" style="min-height: 200px;">
                            <div class="card-body">
                                <h5 class="card-title">Upload Video</h5>
                                <p class="card-text">Select a course to upload video materials.</p>
                                <a href="upload_video.php" class="btn btn-primary">Upload Video</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm" style="min-height: 200px;">
                            <div class="card-body">
                                <h5 class="card-title">Material</h5>
                                <p class="card-text">Access and manage your course materials.</p>
                                <a href="view_materials.php" class="btn btn-primary">View Materials</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm" style="min-height: 200px;">
                            <div class="card-body">
                                <h5 class="card-title">Edit Course</h5>
                                <p class="card-text">Modify existing courses and update content.</p>
                                <a href="./edit_course.php" class="btn btn-primary">Edit Existing Course</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm" style="min-height: 200px;">
                            <div class="card-body">
                                <h5 class="card-title">Edit Videos & Materials</h5>
                                <p class="card-text">Update or delete your course videos and materials.</p>
                                <a href="./edit_content.php" class="btn btn-primary">Manage Content</a>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-3">
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Theme Toggle Functionality
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const htmlElement = document.documentElement;
    
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-bs-theme', savedTheme);
    updateThemeIcon(savedTheme);
    
    themeToggle.addEventListener('click', () => {
        const currentTheme = htmlElement.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        htmlElement.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });
    
    function updateThemeIcon(theme) {
        themeIcon.className = theme === 'light' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
    }
    
    // Active Link Functionality
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if(!this.href.includes('logout.php')) {
                e.preventDefault(); // Prevent default for non-logout links
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Get the section id from href
                const sectionId = this.getAttribute('href').substring(1);
                loadSection(sectionId);
            }
        });
    });

    // Function to load content sections
    function loadSection(sectionId) {
        // Show loading indicator if needed
        
        // Fetch the appropriate section file
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                document.querySelector('main').innerHTML = data;
            })
            .catch(error => {
                console.error('Error loading section:', error);
                document.querySelector('main').innerHTML = `
                    <div class="alert alert-danger m-4" role="alert">
                        Error loading content. Please try again.
                    </div>`;
            });
    }

    // Load profile section by default
    const defaultSection = 'profile';
    document.querySelector(a[href="#${defaultSection}"]).classList.add('active');
    loadSection(defaultSection);
});
</script>
</body>
</html>z