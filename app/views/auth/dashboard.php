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

$recentActivities = [];
$posts = [];

try {
    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error fetching posts: " . $e->getMessage());
    echo "An error occurred while fetching posts.";
}

try {
    $stmt = $pdo->prepare("SELECT * FROM activities ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $recentActivities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {

    error_log("Error fetching activities: " . $e->getMessage());
    echo "An error occurred while fetching recent activities.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postText = isset($_POST['post_text']) ? htmlspecialchars(trim($_POST['post_text'])) : null;
    $postType = isset($_POST['post_type']) ? htmlspecialchars(trim($_POST['post_type'])) : null;

    $imagePath = null;
    $videoPath = null;

    if (empty($postText)) {
        echo "Post text is required.";
        exit();
    }
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === 0) {
        $uploadDir = realpath(__DIR__ . '/../../public/uploads/images/');
        if (!$uploadDir) {
            $uploadDir = __DIR__ . '/../../public/uploads/images/';
            if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
                echo "Failed to create upload directory.";
                exit();
            }
        }

        $imageName = uniqid() . '_' . basename($_FILES['post_image']['name']);
        $imagePath = '/public/uploads/images/' . $imageName;

        $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['post_image']['type'], $allowedImageTypes)) {
            if (!move_uploaded_file($_FILES['post_image']['tmp_name'], $uploadDir . '/' . $imageName)) {
                echo "Failed to upload image.";
                exit();
            }
        } else {
            echo "Invalid image type.";
            exit();
        }
    }

    if (isset($_FILES['post_video']) && $_FILES['post_video']['error'] === 0) {
        $uploadDir = realpath(__DIR__ . '/../../public/uploads/videos/');
        if (!$uploadDir) {
            $uploadDir = __DIR__ . '/../../public/uploads/videos/';
            if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
                echo "Failed to create upload directory.";
                exit();
            }
        }

        $videoName = uniqid() . '_' . basename($_FILES['post_video']['name']);
        $videoPath = '/public/uploads/videos/' . $videoName;

        $allowedVideoTypes = ['video/mp4', 'video/webm', 'video/ogg'];
        if (in_array($_FILES['post_video']['type'], $allowedVideoTypes)) {
            if (!move_uploaded_file($_FILES['post_video']['tmp_name'], $uploadDir . '/' . $videoName)) {
                echo "Failed to upload video.";
                exit();
            }
        } else {
            echo "Invalid video type.";
            exit();
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO posts (post_text, post_image, post_video, post_type, user_id, created_at) 
                               VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$postText, $imagePath, $videoPath, $postType, $_SESSION['user']['id']]);

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();

    } catch (Exception $e) {
        error_log("Error inserting post: " . $e->getMessage());
        echo "An error occurred while inserting the post.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" 
          integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="public/assets/dashStyle.css"/>

    <title>Dashboard</title>
</head>
<body>
    <div class="container">
        <nav>
            <div class="nav-left">
                <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Guest') ?></h1>
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


            <div class="main-right">
                <div class="recent">
                    <h2>Recent Activities</h2>
                    <ul>
                        <?php if (!empty($recentActivities)): ?>
                            <?php foreach ($recentActivities as $activity): ?>
                                <li><?= htmlspecialchars($activity['activities'] . ' - ' . $activity['created_at']) ?></li>
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
                                        <img src="<?= htmlspecialchars($post['post_image']) ?>" alt="User post image" class="post-image">
                                    <?php elseif ($post['post_video']): ?>
                                        <video controls class="post-video">
                                            <source src="<?= htmlspecialchars($post['post_video']) ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php else: ?>
                                        
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

