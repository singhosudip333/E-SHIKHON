<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include connection only if not already included
if (!isset($conn)) {
    include 'connection.php';
}

$success = '';

// Check for session
if (!isset($_SESSION['id'])) {
    echo "Unauthorized access";
    exit;
}

$instructor_id = $_SESSION['id'];

// Fetch instructor details if not already fetched
if (!isset($instructor)) {
    $query = "SELECT * FROM instructor WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $instructor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $instructor = $result->fetch_assoc();
    
    // Set profile image path
    $profileImagePath = $instructor['profile_image'] ? "../uploads/" . basename($instructor['profile_image']) : 'default_profile_image.jpg';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $bio = $_POST['bio'];
    $portfolio_link = $_POST['portfolio_link'];
    $field_expertise = $_POST['field_expertise'];
    $profile_image = $_FILES['profile_image'];

    if ($profile_image && $profile_image['error'] === 0) {
        $targetDir = "../uploads/";
        $fileName = basename($profile_image['name']);
        $targetFilePath = $targetDir . $fileName;
        move_uploaded_file($profile_image['tmp_name'], $targetFilePath);
    }

 
    $sql = "UPDATE instructor SET full_name = ?, phone = ?, bio = ?, portfolio_link = ?, field_expertise = ?";
    $params = [$full_name, $phone, $bio, $portfolio_link, $field_expertise];

    if (isset($targetFilePath)) {
        $sql .= ", profile_image = ?";
        $params[] = $targetFilePath;
    }

    $sql .= " WHERE id = ?";
    $params[] = $instructor_id;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

    if ($stmt->execute()) {
        header("Location: in_dash.php#profile"); 
        exit;
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- Update the HTML structure -->
<div class="row">
    <div class="col-12">
        <form id="profileForm" class="profile-form" method="POST" enctype="multipart/form-data">
            <div class="profile-header mb-4">
                <div class="profile-img-wrapper">
                    <img id="profileImage" src="<?php echo htmlspecialchars($profileImagePath); ?>" alt="Profile Image" class="profile-img">
                    <label for="profileImageUpload" class="profile-img-btn">📷</label>
                    <input type="file" id="profileImageUpload" name="profile_image" style="display: none;" onchange="uploadProfileImage()">
                </div>
                <div>
                    <h2 class="profile-name"><?php echo htmlspecialchars($instructor['full_name']); ?></h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullName" name="full_name" 
                           value="<?php echo htmlspecialchars($instructor['full_name'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" 
                           value="<?php echo htmlspecialchars($instructor['phone'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="fieldExpertise" class="form-label">Field of Expertise</label>
                <input type="text" class="form-control" id="fieldExpertise" name="field_expertise" 
                       value="<?php echo htmlspecialchars($instructor['field_expertise'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="3"><?php echo htmlspecialchars($instructor['bio'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="portfolioLink" class="form-label">Portfolio Link</label>
                <input type="url" class="form-control" id="portfolioLink" name="portfolio_link" 
                       value="<?php echo htmlspecialchars($instructor['portfolio_link'] ?? ''); ?>">
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success mt-3" id="successMessage"><?php echo $success; ?></div>
            <?php endif; ?>
        </form>
    </div>
</div>

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

// Hide success message after 3 seconds
setTimeout(function() {
    const successMessage = document.getElementById("successMessage");
    if (successMessage) {
        successMessage.style.display = "none";
    }
}, 3000);
</script>
