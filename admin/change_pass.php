<?php
session_start();
include("backend/connection.php");

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $admin_id = $_SESSION['admin_id'];

    // Fetch the current password from the database (plain text comparison)
    $stmt = $conn->prepare("SELECT password FROM admin WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    // Verify current password
    if ($current_password === $stored_password) {
        if ($new_password === $confirm_password) {
            // Update the new password (plain text)
            $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $new_password, $admin_id);
            if ($stmt->execute()) {
                $success = "Password changed successfully!";
            } else {
                $error = "Error updating password. Please try again.";
            }
            $stmt->close();
        } else {
            $error = "New password and confirmation do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
    $conn->close();
}
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            margin-top: 30px;
        }
        .strength-bar {
            height: 5px;
            margin-top: 5px;
            transition: width 0.3s ease-in-out;
        }
        .strength-bar.weak { width: 33%; background-color: red; }
        .strength-bar.medium { width: 66%; background-color: orange; }
        .strength-bar.strong { width: 100%; background-color: green; }
        .message {
            margin-bottom: 15px;
            text-align: center;
        }
        .message.success { color: green; }
        .message.error { color: red; }
    </style>
</head>
<body>
    <header class="header">
        <h1>Admin - Change Password</h1>
    </header>
    <div class="container">
        <?php if (isset($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Change Password</h4>
                    </div>
                    <div class="card-body">
                        <form  method="post">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required oninput="checkStrength()">
                                <div class="strength-bar" id="strength-bar"></div>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function checkStrength() {
            var strengthBar = document.getElementById('strength-bar');
            var password = document.getElementById('new_password').value;
            var strength = 0;
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
