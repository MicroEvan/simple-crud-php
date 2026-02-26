<?php
session_start(); // Start the session

// Include the database connection
include 'conn.php';

// Initialize variables
$error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password using password_verify and check if the user is active
        if (password_verify($password, $user['password'])) {
            if ($user['status'] === 'active') {
                // Set session variables and redirect to the dashboard
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on user role
                if ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php"); // Redirect to admin dashboard
                } else {
                    header("Location: user_dashboard.php"); // Redirect to user dashboard
                }
                exit();
            } else {
                $error = 'Your account is inactive. Please contact an administrator for activation.';
            }
        } else {
            $error = 'Invalid password. Please try again.';
        }
    } else {
        $error = 'No user found with that email.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Login - Chato Certificates</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #198754 0%, #157347 50%, #0f5132 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.2);
            padding: 40px 36px 32px;
            width: 100%;
            max-width: 420px;
            text-align: center;
        }
        .login-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #40bf80, #198754);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }
        .login-icon i {
            font-size: 32px;
            color: #fff;
        }
        .login-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 24px;
        }
        .form-control {
            border-radius: 24px;
            padding: 10px 20px;
            font-size: 14px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.15);
        }
        .input-group {
            position: relative;
        }
        .input-group .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            z-index: 5;
            font-size: 18px;
        }
        .btn-login {
            border-radius: 24px;
            padding: 10px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            background: #222;
            color: #fff;
            border: none;
            transition: background 0.2s;
        }
        .btn-login:hover {
            background: #198754;
            color: #fff;
        }
        .login-footer a {
            color: #198754;
            text-decoration: none;
            font-size: 14px;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
        .alert {
            border-radius: 12px;
            font-size: 14px;
            text-align: left;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-icon">
        <img src="logo.ico" alt="Logo" style="width:40px;height:40px;">
    </div>
    <div class="login-title">Login to your account</div>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="mb-3 input-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            <button type="button" class="toggle-password" onclick="togglePassword()">
                <i class="bi bi-eye-slash" id="toggleIcon"></i>
            </button>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-login">Log In</button>
        </div>
    </form>

    <div class="login-footer">
        <a href="register.php">Don't have an account? Register</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            pwd.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
</body>
</html>
