<?php
session_start();
include 'config.php'; 

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        // Registration logic
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $conpassword = trim($_POST['confirm_password']);

        if (empty($username) || empty($email) || empty($password) || empty($conpassword)) {
            $error = "Please input on all fields.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } elseif ($password !== $conpassword) {
            $error = "Passwords do not match.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
            $stmt->execute([$email, $username]);
            if ($stmt->rowCount() > 0) {
                $error = "Email or username is already registered.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$username, $email, $hashedPassword])) {
                    $success = "Registration successful! Please login.";
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
    } elseif (isset($_POST['login'])) {
        // Login logic
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            $error = "Username and password are required.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in || Sign up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form action="" method="POST">
            <h1>Create Account</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>or use your email for registration</span>
            <div class="infield">
                <input type="text" placeholder="Username" name="username" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="email" placeholder="Email" name="email" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="password" placeholder="Password" name="password" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="password" placeholder="Confirm Password" name="confirm_password" required />
                <label></label>
            </div>
            <?php if ($error): ?>
                <p class ="error"><?= htmlspecialchars($error) ?></p>
            <?php elseif ($success): ?>
                <p class="success"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
            <button type="submit" name="register">Sign Up</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form action="" method="POST">
            <h1>Sign in</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>or use your account</span>
            <div class="infield">
                <input type="text" placeholder="Username" name="username" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="password" placeholder="Password" name="password" required />
                <label></label>
            </div>
            <?php if ($error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <a href="#" class="forgot">Forgot your password?</a>
            <button type="submit" name="login">Sign In</button>
        </form>
    </div>
    <div class="overlay-container" id="overlayCon">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Hello, Friend!</h1>
                <p>Register to Help others</p>
                <button id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Welcome Back!</h1>
                <p>Continue Helping Others</p>
                <button id="signUp">Sign Up</button>
            </div>
        </div>
        <button id="overlayBtn"></button>
    </div>
</div>

<footer>
</footer>

<script>
    const container = document.getElementById('container');
    const overlayBtn = document.getElementById('overlayBtn');

    overlayBtn.addEventListener('click', () => {
        container.classList.toggle('right-panel-active');
        overlayBtn.classList.remove('btnScaled');
        window.requestAnimationFrame(() => {
            overlayBtn.classList.add('btnScaled');
        });
    });
</script>

</body>
</html>
