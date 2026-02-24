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
    <title>Welcome to the Certificate Management System</title>
</head>
<body>
<div class="container text-center">
    <h1 class="mt-5">Welcome to the Certificate Management System</h1>
    <p class="lead">Manage your certificates and user accounts efficiently.</p>
    
    <div class="mt-4">
        <a href="login.php" class="btn btn-primary btn-lg">Login</a>
        <a href="register.php" class="btn btn-secondary btn-lg">Register</a>
    </div>

    <footer class="mt-5">
        <p>&copy; <?php echo date("Y"); ?> Chato Electrical & Tankers Equipment Ltd</p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
