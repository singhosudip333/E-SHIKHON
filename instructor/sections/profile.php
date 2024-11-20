<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['id'])) {
    die("No session found");
}

require_once 'connection.php';

// Debug: Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$instructor_id = $_SESSION['id'];

$query = "SELECT * FROM instructor WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result = $stmt->get_result();
$instructor = $result->fetch_assoc();

// Debug: Check if instructor data is fetched
if (!$instructor) {
    die("No instructor data found for ID: " . $instructor_id);
}

// Set profile image path
$profileImagePath = $instructor['profile_image'] ? 
    "../uploads/" . basename($instructor['profile_image']) : 
    '../../assets/default_profile.jpg';

?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="border-bottom pb-2">Profile Settings</h2>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form id="profileForm" class="profile-form" method="POST" enctype="multipart/form-data" >
                        <!-- Profile Image Section -->
                        <div class="text-center mb-4 position-relative">
                            <div class="profile-image-container mx-auto">
                                <img id="profileImage" 
                                     src="<?php echo htmlspecialchars($profileImagePath); ?>" 
                                     alt="Profile Image" 
                                     class="rounded-circle profile-image">
                                <label for="profileImageUpload" class="upload-icon">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                                <input type="file" 
                                       id="profileImageUpload" 
                                       name="profile_image" 
                                       class="d-none" 
                                       accept="image/*"
                                       onchange="uploadProfileImage()">
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control" 
                                           id="fullName" 
                                           name="full_name" 
                                           value="<?php echo htmlspecialchars($instructor['full_name'] ?? ''); ?>" 
                                           required>
                                    <label for="fullName">Full Name</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel" 
                                           class="form-control" 
                                           id="phone" 
                                           name="phone" 
                                           value="<?php echo htmlspecialchars($instructor['phone'] ?? ''); ?>" 
                                           required>
                                    <label for="phone">Phone Number</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control" 
                                           id="fieldExpertise" 
                                           name="field_expertise" 
                                           value="<?php echo htmlspecialchars($instructor['field_expertise'] ?? ''); ?>" 
                                           required>
                                    <label for="fieldExpertise">Field of Expertise</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" 
                                              id="bio" 
                                              name="bio" 
                                              style="height: 120px"><?php echo htmlspecialchars($instructor['bio'] ?? ''); ?></textarea>
                                    <label for="bio">Bio</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="url" 
                                           class="form-control" 
                                           id="portfolioLink" 
                                           name="portfolio_link" 
                                           value="<?php echo htmlspecialchars($instructor['portfolio_link'] ?? ''); ?>">
                                    <label for="portfolioLink">Portfolio Link</label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="bi bi-check2-circle me-2"></i>Save Changes
                            </button>
                        </div>

                        <!-- Debug Info (remove in production) -->
                        <div id="debugInfo" class="mt-3 small text-muted">
                            Session ID: <?php echo $_SESSION['id']; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Profile specific styles */
.profile-image-container {
    position: relative;
    width: 150px;
    height: 150px;
    margin-bottom: 1rem;
}

.profile-image {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.upload-icon {
    position: absolute;
    bottom: 0;
    right: 0;
    background: var(--bs-primary);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-icon:hover {
    transform: scale(1.1);
}

.form-floating > .form-control {
    border-radius: 8px;
    border: 1px solid rgba(0,0,0,0.1);
}

.form-floating > .form-control:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.15);
}

.btn-primary {
    border-radius: 8px;
    padding: 0.75rem 2rem;
    font-weight: 500;
}

/* Dark theme adjustments */
[data-bs-theme="dark"] .card {
    background-color: var(--sidebar-bg);
}

[data-bs-theme="dark"] .profile-image {
    border-color: var(--sidebar-bg);
}

[data-bs-theme="dark"] .form-floating > .form-control {
    background-color: var(--sidebar-bg);
    border-color: rgba(255,255,255,0.1);
    color: var(--text-color);
}

[data-bs-theme="dark"] .form-floating > label {
    color: rgba(255,255,255,0.7);
}
</style>

<script>
function uploadProfileImage() {
    const input = document.getElementById('profileImageUpload');
    const preview = document.getElementById('profileImage');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Form submission
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Saving...';
    submitBtn.disabled = true;

    fetch('sections/update_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Server response:', data);
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${data.status === 'success' ? 'success' : 'danger'} alert-dismissible fade show mt-3`;
        alertDiv.innerHTML = `
            ${data.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        this.appendChild(alertDiv);

        if(data.status === 'success') {
            // Refresh the page after successful update
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
        alertDiv.innerHTML = `
            Error updating profile. Please try again.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        this.appendChild(alertDiv);
    })
    .finally(() => {
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
    });
});
</script>