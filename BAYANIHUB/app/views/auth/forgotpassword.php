<!-- forgotpassword.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="?page=forgotpassword">
    <label for="email">Enter your email to reset your password:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Submit</button>
</form>

<a href="?page=Login">Back to Login</a>

</body>
</html>
