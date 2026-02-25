<?php
session_start();
include 'conn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT name, email FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Profile - Chato Certificates</title>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="content-wrapper">
<div class="container" style="max-width:600px;">

    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="bi bi-person-circle me-2" style="color:var(--primary)"></i>My Profile</h2>
    </div>

    <!-- Profile Card -->
    <div class="card-chato text-center">
        <div style="width:80px;height:80px;background:linear-gradient(135deg,#4da3ff,#0d6efd);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="bi bi-person-fill" style="font-size:36px;color:#fff;"></i>
        </div>
        <h4 style="font-weight:600;color:#333;margin-bottom:4px;"><?php echo htmlspecialchars($user['name']); ?></h4>
        <p style="color:#777;font-size:14px;margin-bottom:24px;"><?php echo htmlspecialchars($user['email']); ?></p>

        <div class="text-start" style="background:#f8f9fa;border-radius:12px;padding:20px;margin-bottom:20px;">
            <div class="d-flex justify-content-between mb-2">
                <span style="color:#777;font-size:14px;">Name</span>
                <span style="font-weight:500;font-size:14px;"><?php echo htmlspecialchars($user['name']); ?></span>
            </div>
            <hr style="margin:8px 0;border-color:#eee;">
            <div class="d-flex justify-content-between">
                <span style="color:#777;font-size:14px;">Email</span>
                <span style="font-weight:500;font-size:14px;"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
        </div>

        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#updateModal"><i class="bi bi-pencil-square me-1"></i>Update Profile</button>
    </div>

</div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="update_profile.php">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalLabel"><i class="bi bi-pencil-square me-2" style="color:var(--primary)"></i>Update Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">New Password <span style="color:#999;font-weight:400;">(leave blank to keep current)</span></label>
            <input type="password" class="form-control" id="password" name="password">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
