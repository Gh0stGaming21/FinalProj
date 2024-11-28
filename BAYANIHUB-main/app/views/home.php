<?php
session_start();  


if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php?route=auth/login');  
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

    <p>This is your home page.</p>

    <p><a href="/index.php?route=auth/logout">Logout</a></p> 

</body>
</html>
