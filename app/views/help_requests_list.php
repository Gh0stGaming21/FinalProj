<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controller = new HelpRequestController();
$helpRequests = $controller->getHelpRequests();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Requests</title>
</head>
<body>
    <h1>Help Requests</h1>

    <h2>Submit a Help Request</h2>
    <form action="?page=help_requests" method="POST">
        <?php if (isset($_SESSION['user'])): ?>
            <p><strong>User:</strong> <?= htmlspecialchars($_SESSION['user']['name']) ?> (ID: <?= htmlspecialchars($_SESSION['user']['id']) ?>)</p>
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user']['id']) ?>">
        <?php else: ?>
            <p>You must be logged in to submit a help request.</p>
            <a href="?page=login">Login</a>
            <?php exit; ?>
        <?php endif; ?>

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
        <textarea id="description" name="description" rows="4" cols="50" required></textarea>
        <br>

        <input type="submit" value="Submit Request">
    </form>

    <h2>Recent Help Requests</h2>
    <?php if (!empty($helpRequests)): ?>
        <table border="1">
            <tr>
                <th>User</th>
                <th>Category</th>
                <th>Description</th>
                <th>Status</th>
                <th>Submitted At</th>
            </tr>
            <?php foreach ($helpRequests as $request): ?>
                <tr>
                    <td><?= htmlspecialchars($request['user_name']) ?></td>
                    <td><?= htmlspecialchars($request['category']) ?></td>
                    <td><?= htmlspecialchars($request['description']) ?></td>
                    <td><?= htmlspecialchars($request['status']) ?></td>
                    <td><?= htmlspecialchars($request['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No help requests found.</p>
    <?php endif; ?>

    <a href="?page=dashboard">Back to Dashboard</a>
</body>
</html>
