<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
if (!isset($_SESSION['user'])) { 
    header('Location: login.php'); 
    exit(); 
}

// Assuming you have a database connection and fetching pending requests
// $pendingRequests = fetchPendingRequests(); // Replace with your actual fetching logic
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" 
          integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./public/assets/adminstyles.css"> 
    <title>Admin Dashboard</title>
</head>
<body>
<div class="container">
    <nav>
        <div class="nav-left">
            <div class="left">
                <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['name']); ?></h1>
            </div>
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
            <a href="?page=logout"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </nav>

    <div class="main-content">
        <!-- Left Section -->
        <div class="main-left">
            <h2>Pending Help Requests</h2>
            <div class="requests-card">
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>   
                    </thead>
                    <tbody>
                        <?php if (!empty($pendingRequests)): ?>
                            <?php foreach ($pendingRequests as $request): ?>
                                <tr>
                                    <td><?= htmlspecialchars($request['user_name']) ?></td>
                                    <td><?= htmlspecialchars($request['category']) ?></td>
                                    <td><?= htmlspecialchars($request['description']) ?></td>
                                    <td><?= htmlspecialchars($request['created_at']) ?></td>
                                    <td>
                                        <form action="/approve_request.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                            <button type="submit" class="action-button approve">Approve</button>
                                        </form>
                                        <form action="/reject_request.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                            <button type="submit" class="action-button reject">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No pending requests found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
    </div>
</div>
</body>
</html>
