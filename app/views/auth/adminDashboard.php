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
</body>
</html>
