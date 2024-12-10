<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Requests</title>
</head>
<body>
    <h1>Help Requests</h1>
    <form action="?page=help_requests" method="POST">
        <label>Category: <input type="text" name="category" required></label><br>
        <label>Description: <textarea name="description" required></textarea></label><br>
        <input type="submit" value="Submit Request">
    </form>

    <h2>Recent Help Requests</h2>
    <ul>
        <?php if (!empty($helpRequests)): ?>
            <?php foreach ($helpRequests as $request): ?>
                <li>
                    <strong>Category:</strong> <?= htmlspecialchars($request['category']) ?> |
                    <strong>Description:</strong> <?= htmlspecialchars($request['description']) ?> |
                    <strong>User:</strong> <?= htmlspecialchars($request['name']) ?> |
                    <strong>Status:</strong> <?= htmlspecialchars($request['status']) ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No help requests found.</li>
        <?php endif; ?>
    </ul>

    <a href="?page=dashboard">Back to Dashboard</a>
</body>
</html>
