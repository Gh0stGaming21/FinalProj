<?php 
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../controllers/HelpRequestController.php';

session_start();

if (!isset($_SESSION['user'])) { 
    header('Location: login.php'); 
    exit(); 
}

$helpRequestController = new HelpRequestController();

$helpRequestController->handleFormSubmission();

$helpRequests = $helpRequestController->getHelpRequests();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" 
          integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./public/assets/helpStyle.css">
    <title>Help Requests</title>
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
        <!-- Left Section: Recent Help Requests -->
        <div class="main-left">
            <h2>Recent Help Requests</h2>
            <div class="posts">
                <ul>
                    <?php if (!empty($helpRequests)): ?>
                        <?php foreach ($helpRequests as $request): ?>
                            <li>
                                <strong>User:</strong> <?= htmlspecialchars($request['user_name']) ?><br>
                                <strong>Category:</strong> <?= htmlspecialchars($request['category']) ?><br>
                                <strong>Description:</strong> <?= htmlspecialchars($request['description']) ?><br>
                                <strong>Status:</strong> <?= htmlspecialchars($request['status']) ?><br>
                                <strong>Submitted At:</strong> <?= htmlspecialchars($request['created_at']) ?><br>
                                <hr>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No help requests found.</p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Right Section: Create Help Request -->
        <div class="main-right">
            <div class="create-post">
                <h2>Submit a Help Request</h2>
                <form action="?page=help_requests" method="POST">
                    <label for="category">Category:</label>
                    <select name="category" id="category" required>
                        <option value="" disabled selected>Select a category</option>
                        <option value="Education">Education</option>
                        <option value="Health">Health</option>
                        <option value="Tech Support">Tech Support</option>
                        <option value="Other">Other</option>
                    </select>
                    <br>

                    <label for="description">Description:</label><br>
                    <textarea id="description" name="description" required></textarea>
                    <br>

                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user']['id']) ?>">
                    <button type="submit">Submit Request</button>
                </form>
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