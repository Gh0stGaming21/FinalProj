<?php
session_start();

// Only allow admin users to access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}

require_once 'config/Database.php';
require_once 'models/EventModel.php';

$db = new Database();
$eventModel = new EventModel($db->connect());

// Fetch pending help requests
$pendingRequests = $eventModel->getPendingRequests(); // Replace with your method for fetching help requests

// Fetch RSVP participation data
$rsvps = $eventModel->getAllRSVPs();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/public/assets/adminstyles.css"> 
</head>
<body>
    <h1>Admin Dashboard</h1>

    <h2>Pending Help Requests</h2>
    <table>
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
                            <form action="/approve_request.php" method="POST">
                                <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                <button type="submit">Approve</button>
                            </form>
                            <form action="/reject_request.php" method="POST">
                                <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                <button type="submit">Reject</button>
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

    <h2>RSVP Participation</h2>
    <table>
        <thead>
            <tr>
                <th>Event Title</th>
                <th>User Name</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rsvps)): ?>
                <?php foreach ($rsvps as $rsvp): ?>
                    <tr>
                        <td><?= htmlspecialchars($rsvp['event_title']); ?></td>
                        <td><?= htmlspecialchars($rsvp['user_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No RSVP participation data found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
