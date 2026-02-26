<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not logged in or not an admin
    exit();
}

require_once 'conn.php';

//getting certificate_id from url
$certificate_id = $_GET['id'];

//create query object
$query = "SELECT * FROM `certificate` WHERE certificate_id=$certificate_id";
//select data associated to certificate_id from certificate
$results = mysqli_query($conn, $query);

while($row = mysqli_fetch_array($results)){
    $customer = $row['customer'];
    $registration_number = $row['registration_number'];
    $vin_number = $row['vin_number'];
    $tank_description = $row['tank_description'];
    $trailer_compartments = $row['trailer_compartments'];
    $job_number = $row['job_number'];
    $expiry_date = $row['expiry_date'];
    $expiry_date = $row['issue_date'];
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
    <link href="style.css" rel="stylesheet">
    <title>Edit Certificate - Chato Certificates</title>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="content-wrapper">
<div class="container" style="max-width:720px;">

    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="bi bi-pencil-square me-2" style="color:var(--primary)"></i>Edit Certificate</h2>
        <a href="admin_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>

    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><strong>Please fix the following:</strong>
            <ul class="mb-0 mt-1">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <div class="card-chato">
        <form method="post" name="edit-form" action="certeditprocess.php">
            <input type="hidden" name="certificate_id" value="<?php echo $certificate_id; ?>">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Customer</label>
                    <input type="text" name="customer" class="form-control" value="<?php echo htmlspecialchars($customer); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Registration Number</label>
                    <input type="text" name="registration_number" class="form-control" value="<?php echo htmlspecialchars($registration_number); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">VIN Number</label>
                    <input type="text" name="vin_number" class="form-control" value="<?php echo htmlspecialchars($vin_number); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Tank Description</label>
                    <textarea name="tank_description" class="form-control" rows="3"><?php echo htmlspecialchars($tank_description); ?></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Trailer Compartments</label>
                    <input type="number" name="trailer_compartments" class="form-control" value="<?php echo htmlspecialchars($trailer_compartments); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Job Number</label>
                    <input type="number" name="job_number" class="form-control" value="<?php echo htmlspecialchars($job_number); ?>">
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-6">
                    <label class="form-label">Issue Date</label>
                    <input type="date" name="issue_date" class="form-control" value="<?php echo htmlspecialchars($issue_date); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control" value="<?php echo htmlspecialchars($expiry_date); ?>">
                </div>
            </div>
            <hr style="margin:24px 0 16px; border-color:#eee;">
            <div class="d-flex justify-content-end gap-2">
                <a class="btn btn-outline-secondary" href="admin_dashboard.php">Cancel</a>
                <button class="btn btn-primary" type="submit" name="update"><i class="bi bi-check-lg me-1"></i>Update</button>
            </div>
        </form>
    </div>

</div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
