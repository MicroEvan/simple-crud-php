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
    <title>Login</title>
</head>
<body>
<div class="container">
    <h2 class="mt-5">Login</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
