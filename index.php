<?php
session_start(); // Start the session

// Check if the user is logged in and redirect to the appropriate dashboard
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: admin_dashboard.php"); // Redirect to admin dashboard
        exit();
    } else {
        header("Location: user_dashboard.php"); // Redirect to user dashboard
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Chato Certificates</title>
    <style>
        body { background: linear-gradient(135deg, #198754 0%, #157347 50%, #0f5132 100%); display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    </style>
</head>
<body>

<div class="text-center" style="max-width:480px; width:100%; padding:20px;">
    <div style="background:#fff; border-radius:16px; box-shadow:0 8px 40px rgba(0,0,0,0.2); padding:48px 36px;">
        <div style="width:80px;height:80px;background:linear-gradient(135deg,#40bf80,#198754);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <i class="bi bi-shield-check" style="font-size:36px;color:#fff;"></i>
        </div>
        <h1 style="font-size:24px;font-weight:700;color:#333;margin-bottom:8px;">Chato Certificates</h1>
        <p style="color:#777;font-size:14px;margin-bottom:32px;">Manage your pressure test certificates efficiently and securely.</p>

        <div class="d-grid gap-3">
            <a href="login.php" class="btn btn-dark-login" style="border-radius:24px; padding:12px; font-size:16px; font-weight:600; background:#222; color:#fff;">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </a>
            <a href="register.php" class="btn" style="border-radius:24px; padding:12px; font-size:16px; font-weight:600; background:var(--primary); color:#fff; border:none;">
                <i class="bi bi-person-plus me-2"></i>Register
            </a>
        </div>
    </div>

    <p style="color:rgba(255,255,255,0.7); font-size:12px; margin-top:20px;">
        &copy; 2014 – <?php echo date("Y"); ?> Chato Electrical &amp; Tankers Equipment Ltd.
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
