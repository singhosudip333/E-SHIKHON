<?php
include("backend/connection.php");
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}

$users = [];

if (isset($_GET['search_email'])) {
    $searchEmail = $_GET['search_email'];
    $query = "SELECT * FROM users WHERE email LIKE ?";
    $stmt = $conn->prepare($query);
    $searchTerm = "%$searchEmail%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $query = "SELECT * FROM users";
    $result = $conn->query($query);
    $users = $result->fetch_all(MYSQLI_ASSOC);
}

if (isset($_POST['ban_user'])) {
    $userId = $_POST['user_id'];
    $banQuery = "UPDATE users SET status = 'ban' WHERE id = ?";
    $banStmt = $conn->prepare($banQuery);
    $banStmt->bind_param("i", $userId);
    $banStmt->execute();
    header("Location: manage_users.php"); // Refresh the page after banning
    exit();
}

if (isset($_POST['reactivate_user'])) {
    $userId = $_POST['user_id'];
    $reactivateQuery = "UPDATE users SET status = 'active' WHERE id = ?";
    $reactivateStmt = $conn->prepare($reactivateQuery);
    $reactivateStmt->bind_param("i", $userId);
    $reactivateStmt->execute();
    header("Location: manage_users.php"); // Refresh the page after reactivation
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
        }

        .search-bar input[type="text"] {
            padding: 12px 18px;
            font-size: 16px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 50px;
            margin-right: 10px;
            transition: width 0.4s ease;
        }

        .search-bar input[type="text"]:focus {
            width: 400px;
            border-color: #007bff;
        }

        .search-bar button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            overflow-x: auto;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
            color: #333;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .ban-button, .reactivate-button {
            padding: 8px 15px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .ban-button {
            background-color: #dc3545;
            color: white;
        }

        .ban-button:hover {
            background-color: #c82333;
        }

        .reactivate-button {
            background-color: #28a745;
            color: white;
        }

        .reactivate-button:hover {
            background-color: #218838;
        }

        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-banned {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Manage Users</h1>

        <div class="search-bar">
            <input type="text" id="search-email" placeholder="Search by email" onkeyup="liveSearch()">
            <button type="button" onclick="window.location.href='manage_users.php'">Clear</button>
        </div>

        <table id="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Phone</th>
                    <th>Date of Birth</th>
                    <th>Occupation</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo htmlspecialchars($user['dob']); ?></td>
                        <td><?php echo htmlspecialchars($user['occupation']); ?></td>
                        <td><?php echo htmlspecialchars($user['address']); ?></td>
                        <td>
                            <span class="<?php echo $user['status'] === 'ban' ? 'status-banned' : 'status-active'; ?>">
                                <?php echo htmlspecialchars(ucfirst($user['status'])); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['status'] === 'ban'): ?>
                                <form method="POST" action="manage_users.php" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="reactivate_user" class="reactivate-button">Reactivate</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="manage_users.php" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="ban_user" class="ban-button">Ban</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function liveSearch() {
            const searchValue = document.getElementById('search-email').value;
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'manage_users.php?search_email=' + searchValue, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(xhr.responseText, 'text/html');
                    const newTbody = doc.getElementById('users-table').querySelector('tbody');
                    document.getElementById('users-table').querySelector('tbody').innerHTML = newTbody.innerHTML;
                }
            };
            xhr.send();
        }
    </script>

</body>
</html>
