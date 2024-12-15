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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" 
          integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./public/assets/dashStyle.css">
    <title>Dashboard</title>
</head>
<body>
<div class="container">
    <nav>
        <div class="nav-left">
            <h1>Welcome, <?= isset($_SESSION['user']['name']) ? htmlspecialchars($_SESSION['user']['name']) : 'Guest'; ?></h1>
        </div>

        <!-- Toggle Button for Mobile View -->
        <button class="nav-toggle" onclick="toggleNav()">☰</button>

        <div class="nav-center">
            <a href="?page=dashboard"><i class="fa-solid fa-house"></i></a>
            <a href="?page=help_requests"><i class="fa-solid fa-tv"></i></a>
            <a href="?page=resource_sharing"><i class="fa-solid fa-share"></i></a>
            <a href="?page=events"><i class="fa-solid fa-calendar"></i></a>
        </div>

        <div class="nav-right">
            <a href="?page=profile"><i class="fa-solid fa-user"></i></a>
            <a href="?page=logout"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </nav>

    <div class="main-content">
        <!-- Left Section -->
        <div class="main-left">
            <div class="create-post">
                <form method="POST" action="?page=create_post" enctype="multipart/form-data">
                    <div class="ptop">
                        <textarea name="post_text" placeholder="What's your request?" required></textarea>
                    </div>
                    <div class="pbottom">
                        <div class="post-icon">
                            <label>
                                <input type="file" name="post_video" accept="video/*">
                                <i class="fa-solid fa-video red"></i>
                                <p>Live Video</p>
                            </label>
                        </div>
                        <div class="post-icon">
                            <label>
                                <input type="file" name="post_image" accept="image/*">
                                <i class="fa-solid fa-images green"></i>
                                <p>Image Post</p>
                            </label>
                        </div>
                        <div class="post-icon">
                            <label>
                                <input type="radio" name="post_type" value="text">
                                <i class="fa-solid fa-face-grin yellow"></i>
                                <p>Status Post</p>
                            </label>
                        </div>
                    </div>
                    <button type="submit">Post</button>
                </form>
            </div>

            <div class="posts">
                <h2>Posts</h2>
                <ul>
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <li>
                                <h3><?= htmlspecialchars($post['post_text']); ?></h3>
                                <?php if (!empty($post['post_image'])): ?>
                                    <?php if (file_exists($post['post_image'])): ?>
                                        <img src="<?= htmlspecialchars($post['post_image']); ?>" alt="Post Image">
                                    <?php else: ?>
                                        <p>Error: Image file not found.</p>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if (!empty($post['post_video'])): ?>
                                    <video controls>
                                        <source src="<?= htmlspecialchars($post['post_video']); ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                <?php endif; ?>
                                <p>Posted on: <?= htmlspecialchars($post['created_at']); ?></p>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No posts available.</p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Right Section -->
        <div class="main-right">
            <div class="recent-activities">
                <h2>Recent Activities</h2>
                <ul>
                    <?php if (!empty($recentActivities)): ?>
                        <?php foreach ($recentActivities as $activity): ?>
                            <li>
                                <p><?= htmlspecialchars($activity['activity_description']); ?> - 
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
</script>
</body>
</html>