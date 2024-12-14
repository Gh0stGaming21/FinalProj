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

$helpRequests = $pdo->prepare("SELECT * FROM help_requests ORDER BY created_at DESC"); 
$helpRequests->execute(); 
$helpRequests = $helpRequests->fetchAll(); 
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
    <title>Help Requests</title>
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
        <!-- Left Section: Recent Help Requests -->
        <div class="main-left">
            <h2>Recent Help Requests</h2>
            <div class="posts">
                <ul>
                    <?php if (!empty($helpRequests)): ?>
                        <?php foreach ($helpRequests as $request): ?>
                            <tr>
                                <td><?= htmlspecialchars($request['user_name'] ?? 'Unknown User') ?></td>
                                <br>
                                <td><?= htmlspecialchars($request['category'] ?? 'N/A') ?></td>
                                <br>
                                <td><?= htmlspecialchars($request['description'] ?? 'No description provided.') ?></td>
                                <br>
                                <td><?= htmlspecialchars($request['status'] ?? 'N/A') ?></td>
                                <br>
                                <td><?= htmlspecialchars($request['created_at'] ?? 'N/A') ?></td>
                                <hr>
                                <br><br><br>
                            </tr>
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

                    <button type="submit"> Submit Request</button>
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
</script>
</body>
</html>