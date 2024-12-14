<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
<link rel="stylesheet" href="./public/assets/style.css">

<div class="container" id="container">
    <!-- Sign Up Form -->
    <div class="form-container sign-up-container">
        <form action="?page=register" method="POST">
            <h1>Sign Up</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>or use your email to register</span>
            <div class="infield">
                <input type="text" placeholder="Name" name="name" />
                <label></label>
            </div>
            <div class="infield">
                <input type="email" placeholder="Email" name="email" />
                <label></label>
            </div>
            <div class="infield">
                <input type="password" placeholder="Password" name="password" />
                <label></label>
            </div>
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
                <?php unset($_SESSION['error']); // Clear the error after displaying it ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <p class="error"><?php echo htmlspecialchars($_SESSION['success']); ?></p>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <button type="submit" name="register">Sign Up</button>
        </form>
    </div>

    <!-- Sign In Form -->
    <div class="form-container sign-in-container">
        <form action="?page=login" method="POST">
            <h1>Sign In</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>or use your account</span>
            <div class="infield">
                <input type="text" placeholder="Email" name="email" />
                <label></label>
            </div>
            <div class="infield">
                <input type="password" placeholder="Password" name="password" />
                <label></label>
            </div>
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
                <?php unset($_SESSION['error']); // Clear the error after displaying it ?>
            <?php endif; ?>
            <button type="submit" name="login">Sign In</button>
            <a href="?page=forgotpassword" class="forgot">Forgot Password?</a>
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

<script>
    const container = document.getElementById ('container');
    const overlayBtn = document.getElementById('overlayBtn');

    // Ensure the Sign Up form is shown first
    container.classList.add('right-panel-active');

    document.getElementById('signIn').addEventListener('click', () => {
        container.classList.remove('right-panel-active');
    });

    document.getElementById('signUp').addEventListener('click', () => {
        container.classList.add('right-panel-active');
    });

    overlayBtn.addEventListener('click', () => {
        container.classList.toggle('right-panel-active');
        overlayBtn.classList.remove('btnScaled');
        window.requestAnimationFrame(() => {
            overlayBtn.classList.add('btnScaled');
        });
    });
</script>