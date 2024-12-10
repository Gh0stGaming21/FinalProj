<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Requests</title>
</head>
<body>
    <h1>Help Requests</h1>

    <!-- Help Request Submission Form -->
    <h2>Submit a Help Request</h2>
    <form action="?page=help_requests" method="POST">
        <label>Category: <input type="text" name="category" required></label><br>
        <label>Description: <textarea name="description" required></textarea></label><br>
        <input type="submit" value="Submit Request">
    </form>

    <!-- List of Recent Help Requests -->
    <h2>Recent Help Requests</h2>
    <?php if (!empty($helpRequests)): ?>
        <table border="1">
            <tr>
                <th>User</th>
                <th>Category</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
            <?php foreach ($helpRequests as $request): ?>
                <tr>
                    <td><?= htmlspecialchars($request['user']) ?></td>
                    <td><?= htmlspecialchars($request['category']) ?></td>
                    <td><?= htmlspecialchars($request['description']) ?></td>
                    <td><?= htmlspecialchars($request['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No help requests found.</p>
    <?php endif; ?>

    <a href="?page=dashboard">Back to Dashboard</a>
</body>
</html>
