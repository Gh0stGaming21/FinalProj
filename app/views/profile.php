<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once './config/Database.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$db = new Database();
$pdo = $db->connect();

$userId = $_SESSION['user']['id'];
$userProfile = null;

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error fetching user profile: " . $e->getMessage());
    echo "An error occurred while fetching your profile.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="public/assets/profileStyle.css">
</head>
<body>
    <header>
        <h1>Welcome to Your Profile</h1>
    </header>

    <div class="container">
        <div class="profile-info">
            <h2>User Profile</h2>
            
            <?php if ($userProfile): ?>
                <p><span>Name:</span> <?= htmlspecialchars($userProfile['name']) ?></p>
                <p><span>Email:</span> <?= htmlspecialchars($userProfile['email']) ?></p>
                <p><span>Role:</span> <?= htmlspecialchars($userProfile['role']) ?></p>
                <p><span>Status:</span> <?= htmlspecialchars($userProfile['status']) ?></p>
                <p><span>Joined on:</span> <?= htmlspecialchars($userProfile['created_at']) ?></p>
            <?php else: ?>
                <p>User profile not found.</p>
            <?php endif; ?>
            
            <a href="?page=dashboard">Back to Dashboard</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Bayanihub@gmail.com</p>
    </footer>
</body>
</html>
