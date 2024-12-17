<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$db = new Database();
$pdo = $db->connect();
$db->validateAdminAccess();

$posts = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC");
$posts->execute();
$posts = $posts->fetchAll();

$recentActivities = $pdo->prepare("SELECT * FROM activities ORDER BY created_at DESC");
$recentActivities->execute();
$recentActivities = $recentActivities->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./public/assets/dashStyle.css">
    <title>Dashboard</title>
</head>
<body>
<div class="container">
    <nav>
        <div class="nav-left">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['name']); ?></h1>
        </div>
        <button class="nav-toggle" onclick="toggleNav()">☰</button>
        <div class="nav-center">
            <a href="?page=dashboard"><i class="fa-solid fa-house"></i></a>
            <a href="?page=help_requests"><i class="fa-solid fa-tv"></i></a>
            <a href="?page=resource_sharing"><i class="fa-solid fa-share"></i></a>
            <a href="?page=events"><i class="fa-solid fa-calendar"></i></a>
        </div>
        <div class="nav-right">
    <a href="?page=profile"><i class="fa-solid fa-user"></i></a>
    <a href="?page=logout" onclick="return confirmLogout();"><i class="fa-solid fa-right-from-bracket"></i></a>
</div>
    </nav>

    <div class="main-content">
        <div class="main-left">
            <div class="create-post">
                <form method="POST" action="?page=create_post">
                    <div class="ptop">
                        <textarea name="post_text" placeholder="What's your request?" required></textarea>
                        <input type="hidden" name="post_type" value="text"> 
                    </div>
                    <div class="post-button-wrapper">
                        <button type="submit" class="post-button">Post</button>
                    </div>
                </form>
            </div>

            <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
                <div class="success-message">Post created successfully!</div>
            <?php endif; ?>

            <div class="posts">
                <h2>Posts</h2>
                <ul>
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <li>
                                <h3><?= htmlspecialchars($post['post_text']); ?></h3>
                                <p>Posted on: <?= htmlspecialchars($post['created_at']); ?></p>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No posts available.</p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="main-right">
            <div class="recent-activities">
            <h2>Recent Activities</h2>
            <ul>
            <?php if (!empty($recentActivities)): ?>
                <?php foreach ($recentActivities as $activity): ?>
                    <li>
                        <p>
                            <strong><?= htmlspecialchars($activity['user_name']); ?></strong> 
                            <?= htmlspecialchars($activity['activity_text']); ?> - 
                            <small><?= htmlspecialchars($activity['created_at']); ?></small>
                        </p>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No recent activities.</p>
            <?php endif; ?>
        </ul>
    </div>
</div>
    </div>
</div>
<script>
    function toggleNav() {
        const navCenter = document.querySelector('.nav-center');
        navCenter.classList.toggle('active');
    }

    function confirmLogout() {
        return confirm("Are you sure you want to log out?");
    }
</script>
</body>
</html>