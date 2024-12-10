<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Requests</title>
</head>
<body>
    <header>
        <h1>Help Requests</h1>
    </header>

    <main>
        <h2>Recent Help Requests</h2>
        <table border="1">
            <tr>
                <th>User</th>
                <th>Category</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
            <?php if (isset($requests) && is_array($requests) && count($requests) > 0): ?>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?= htmlspecialchars($request['name']) ?></td>
                        <td><?= htmlspecialchars($request['category']) ?></td>
                        <td><?= htmlspecialchars($request['description']) ?></td>
                        <td><?= htmlspecialchars($request['status']) ?></td>
                    </tr>
                    
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No help requests found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </main> 
</body>
</html>
