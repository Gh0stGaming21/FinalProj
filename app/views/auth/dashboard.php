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
                <div class='left'>
                <h1>Welcome, <?= isset($_SESSION['user']) && is_array($_SESSION['user']) && isset($_SESSION['user']['name']) ? htmlspecialchars($_SESSION['user']['name']) : 'Guest' ?></h1>
                </div>
            </div>

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
            <div class="main-left">
                <div class="create-post">
                    <form method="POST" action="?page=create_post" enctype="multipart/form-data">
                        <div class="ptop">
                            <textarea name="post_text" placeholder="What's your request?"></textarea>
                        </div>
                        <div class="pbottom">
                            <div class="post-icon">
                                <input type="file" name="post_video" accept="video/*">
                                <i class="fa-solid fa-video ```php
                                red"></i>
                                <p>Live Video</p>
                            </div>

                            <div class="post-icon">
                                <input type="file" name="post_image" accept="image/*">
                                <i class="fa-solid fa-images green"></i>
                                <p>Image Post</p>
                            </div>

                            <div class="post-icon">
                                <input type="radio" name="post_type" value="text">
                                <i class="fa-solid fa-face-grin yellow"></i>
                                <p>Status Post</p>
                            </div>
                        </div>
                        <button type="submit">Post</button>
                    </form>
                </div>
            </div>

            <div class="main-right">
                <div class="recent">
                    <h2>Recent Activities</h2>
                    <ul>
                        <?php if (!empty($recentActivities)): ?>
                            <?php foreach ($recentActivities as $activity): ?>
                                <li>
                                    <?= htmlspecialchars($activity['activity']) ?> - <?= htmlspecialchars($activity['created_at']) ?>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>No recent activities.</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="posts">
    <h2>Posts</h2>
    <ul>
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <li>
                    <h3><?= htmlspecialchars($post['post_text']) ?></h3>
                    <?php if ($post['post_image']): ?>
                        <?php if (file_exists($post['post_image'])): ?>
                            <img src="<?= htmlspecialchars($post['post_image']) ?>" alt="Post Image">
                        <?php else: ?>
                            <p>Error: Image file not found.</p>
                        <?php endif; ?>
                    <?php elseif ($post['post_video']): ?>
                        <video src="<?= htmlspecialchars($post['post_video']) ?>" alt="Post Video"></video>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No posts.</li>
        <?php endif; ?>
    </ul>
</div>
            </div>
        </div>
    </div>

</body>
</html>