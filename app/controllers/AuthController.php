<?php
require_once './app/models/User.php';
require_once './config/Database.php';

class AuthController {
    private $db;
    private $userModel;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->userModel = new User($this->db);
    }

    public function login() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->findByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] !== 'active') {
                    echo "Your account is inactive. Please contact support.";
                    return;
                }
                $_SESSION['user'] = $user;
                header("Location: ?page=dashboard");
                exit;
            }
            $error = "Invalid credentials.";
        }
        include './app/views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if ($this->userModel->create($name, $email, $password)) {
                header("Location: ?page=login");
                exit;
            } else {
                $error = "Failed to register user.";
            }
        }
        include './app/views/auth/register.php';
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];

            $user = $this->userModel->findByEmail($email);
            if ($user) {
                $token = bin2hex(random_bytes(16)); 
                $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

                $stmt = $this->db->prepare("UPDATE users SET reset_token = :token, token_expiry = :expiry WHERE email = :email");
                $stmt->bindParam(':token', $token);
                $stmt->bindParam(':expiry', $expiry);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                header("Location: ?page=resetpassword&token=$token");
                exit;
            } else {
                echo "Email not found.";
            }
        }
        include './app/views/auth/forgotpassword.php';
    }

    public function resetPassword() {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];

            $stmt = $this->db->prepare("SELECT * FROM users WHERE reset_token = :token AND token_expiry > NOW()");
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $newPassword = $_POST['password'];
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    $stmt = $this->db->prepare("UPDATE users SET password = :password, reset_token = NULL, token_expiry = NULL WHERE reset_token = :token");
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->bindParam(':token', $token);
                    $stmt->execute();

                    echo "Your password has been successfully reset.";
                    header("Location: ?page=login");
                    exit;
                }
                include './app/views/auth/resetpassword.php';
            } else {
                echo "Invalid or expired token.";
            }
        } else {
            echo "Token missing.";
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: ?page=login");
        exit;
    }
}
?>
