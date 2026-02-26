<?php
session_start(); // Start the session

// Include the database connection
include 'conn.php';

// Initialize variables
$error = '';
$success = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to insert new user
    $stmt = $conn->prepare("INSERT INTO user (name, email, password, role, status) VALUES (?, ?, ?, 'user', 'inactive')");
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $success = 'Registration successful! Please wait for admin approval.';
    } else {
        $error = 'Registration failed. Please try again.';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Register - Chato Certificates</title>
    <style>
        body {
            margin: 0; padding: 0; min-height: 100vh;
            background: linear-gradient(135deg, #198754 0%, #157347 50%, #0f5132 100%);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .register-card {
            background: #fff; border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.2);
            padding: 40px 36px 32px; width: 100%; max-width: 420px; text-align: center;
        }
        .register-icon {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #40bf80, #198754);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }
        .register-icon i { font-size: 32px; color: #fff; }
        .register-title { font-size: 20px; font-weight: 600; color: #333; margin-bottom: 24px; }
        .form-control {
            border-radius: 24px; padding: 10px 20px; font-size: 14px; border: 1px solid #ddd;
        }
        .form-control:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25,135,84,0.15); }
        .input-group { position: relative; }
        .input-group .toggle-password {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #999; cursor: pointer; z-index: 5; font-size: 18px;
        }
        .btn-register {
            border-radius: 24px; padding: 10px; font-size: 16px; font-weight: 600; width: 100%;
            background: #222; color: #fff; border: none; transition: background 0.2s;
        }
        .btn-register:hover { background: #198754; color: #fff; }
        .register-footer a { color: #198754; text-decoration: none; font-size: 14px; }
        .register-footer a:hover { text-decoration: underline; }
        .alert { border-radius: 12px; font-size: 14px; text-align: left; }
    </style>
</head>
<body>

<div class="register-card">
    <div class="register-icon">
        <i class="bi bi-person-plus"></i>
    </div>
    <div class="register-title">Create your account</div>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your username" required>
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="mb-3 input-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
            <button type="button" class="toggle-password" onclick="togglePassword()">
                <i class="bi bi-eye-slash" id="toggleIcon"></i>
            </button>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-register">Register</button>
        </div>
    </form>

    <div class="register-footer">
        <a href="login.php">Already have an account? Login here</a>
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
