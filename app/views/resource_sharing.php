<?php 
if (!isset($_SESSION['user'])) { 
    header('Location: login.php'); 
    exit(); 
}

$db = new Database(); 
$pdo = $db->connect(); 
$db->validateAdminAccess();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" 
          integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
          <link rel="stylesheet" href="./public/assets/shareStyle.css">
    <title>Resource Sharing Page</title>
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

    <div class="content">
        <h1>Resource Sharing Page</h1>
        <p>This page will allow the admin to manage resource sharing.</p>
        <a href="?page=dashboard">Back to Dashboard</a>
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
