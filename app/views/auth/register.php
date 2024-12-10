<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="./public/assets/style.css">
    <title>Register</title>
</head>
<body>
    <div>
            <div class="form-container sign-up-container">
                <form action="" method="POST">
                    <h1>Sign Up</h1>
                    <div class="social-container">
                        <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                        <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <span>or use your email for registration</span>
                    <div class="infield">
                        <input type="text" placeholder="Name" name="name" required />
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
                    <button type="submit" name="register">Sign Up</button>
                </form>
        </div>

    </div>
<form method="POST" action="?page=register">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <a href="?page=Login">Already Have an account</a>
    <button type="submit">Register</button>
</form>

</body>
</html>
