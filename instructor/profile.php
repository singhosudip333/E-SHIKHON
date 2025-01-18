<?php
include '../connection.php';
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

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instructor Profile - E-SHIKHON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="mb-3">
            <a href="../in_dash.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Profile Information</h4>
                    </div>
                    <div class="card-body">
                        <form id="profileForm">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo htmlspecialchars($instructor['full_name']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($instructor['email']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($instructor['phone']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="experience_years" class="form-label">Years of Experience</label>
                                <input type="number" class="form-control" id="experience_years" name="experience_years" 
                                       value="<?php echo htmlspecialchars($instructor['experience_years']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="field_expertise" class="form-label">Areas of Expertise</label>
                                <textarea class="form-control" id="field_expertise" name="field_expertise" rows="3"><?php echo htmlspecialchars($instructor['field_expertise']); ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="portfolio_link" class="form-label">Portfolio Link</label>
                                <input type="url" class="form-control" id="portfolio_link" name="portfolio_link" 
                                       value="<?php echo htmlspecialchars($instructor['portfolio_link']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4"><?php echo htmlspecialchars($instructor['bio']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="profile_image" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                            </div>

                            <?php if($instructor['profile_image']): ?>
                            <div class="mb-3">
                                <img src="../instructor/uploads/<?php echo htmlspecialchars($instructor['profile_image']); ?>" 
                                     alt="Profile Picture" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            <?php endif; ?>

                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>

                        <hr>

                        <div class="mt-4">
                            <h5>Change Password</h5>
                            <form id="passwordForm">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>

                                <button type="submit" class="btn btn-warning">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Profile Update
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Profile updated successfully');
                    window.location.href = './in_dash.php';
                } else {
                    alert(data.error || 'Error updating profile');
                }
            });
        });

        // Password Change
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if(document.getElementById('new_password').value !== document.getElementById('confirm_password').value) {
                alert('New passwords do not match');
                return;
            }
            
            const formData = new FormData(this);
            
            fetch('change_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Password changed successfully');
                    this.reset();
                } else {
                    alert(data.error || 'Error changing password');
                }
            });
        });
    </script>
</body>
</html>
