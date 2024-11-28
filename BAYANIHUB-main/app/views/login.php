
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php if (isset($error_message)) : ?>
        <p style="color:red;"><?php echo $error_message; ?></p> 
    <?php endif; ?>

    <form method="POST" action="/index.php?route=auth/login">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" placeholder="Enter username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Enter password" required><br><br>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="/index.php?route=auth/register">Register here</a></p>
</body>
</html>
