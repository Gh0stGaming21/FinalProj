<!-- resetpassword.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style ></style>
</head>
<body>
    <h1>Reset Password</h1>
    <form method="POST" action="?page=resetpassword&token=<?php echo $_GET['token']; ?>">
    <label for="password">New Password:</label>
    <input type="password" name="password" id="password" required>
    <button type="submit">Reset Password</button>
</form>


</body>
</html>