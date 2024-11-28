<form method="POST" action="?page=login">
    <h1>Login</h1>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <a href="?page=register">Register</a>
    <a href="?page=forgotpassword">Forgot Password?</a>
</form>

