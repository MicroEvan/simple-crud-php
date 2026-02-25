<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not logged in or not an admin
    exit();
}

require_once 'conn.php';

$errors = [];
$success = '';

if (isset($_POST['Submit'])) {
    // Collect and sanitize input
    $customer = trim($_POST['customer'] ?? '');
    $registration_number = trim($_POST['registration_number'] ?? '');
    $vin_number = trim($_POST['vin_number'] ?? '');
    $tank_description = trim($_POST['tank_description'] ?? '');
    $trailer_compartments = trim($_POST['trailer_compartments'] ?? '');
    $job_number = trim($_POST['job_number'] ?? '');
    $issue_date = trim($_POST['issue_date'] ?? '');
    $expiry_date = trim($_POST['expiry_date'] ?? '');

    // Validate required fields
    if (empty($customer)) $errors[] = "Customer Name is required.";
    if (empty($registration_number)) $errors[] = "Registration Number is required.";
    if (empty($vin_number)) $errors[] = "VIN Number is required.";
    if (empty($tank_description)) $errors[] = "Tank Description is required.";
    if (empty($trailer_compartments)) $errors[] = "Trailer Compartments is required.";
    if (empty($job_number)) $errors[] = "Job Number is required.";
    if (empty($issue_date)) $errors[] = "Issue Date is required.";
    if (empty($expiry_date)) $errors[] = "Expiry Date is required.";

    if (empty($errors)) {
        try {
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("INSERT INTO `certificate` (customer, registration_number, vin_number, tank_description, trailer_compartments, job_number, issue_date, expiry_date, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssissi", $customer, $registration_number, $vin_number, $tank_description, $trailer_compartments, $job_number, $issue_date, $expiry_date, $user_id);

            if ($stmt->execute()) {
                $success = "Certificate added successfully.";
                $stmt->close();
            } else {
                $errors[] = "Could not add the certificate. Please try again.";
            }
        } catch (mysqli_sql_exception $e) {
            error_log("SQL Error in add_certificate.php: " . $e->getMessage());
            $errors[] = "Something went wrong while saving the certificate. Please contact the administrator.";
        } catch (Exception $e) {
            error_log("Error in add_certificate.php: " . $e->getMessage());
            $errors[] = "An unexpected error occurred. Please try again later.";
        }
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
    <title>New Certificate - Chato Certificates</title>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="content-wrapper">
<div class="container" style="max-width:720px;">

    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="bi bi-file-earmark-plus me-2" style="color:var(--primary)"></i>New Certificate</h2>
        <a href="admin_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><strong>Please fix the following:</strong>
            <ul class="mb-0 mt-1">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
            <a href="admin_dashboard.php" class="alert-link ms-2">Back to Dashboard</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card-chato">
        <form action="add_certificate.php" method="post" name="add-form">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="customer" class="form-control" placeholder="Enter customer name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Registration Number</label>
                    <input type="text" name="registration_number" class="form-control" placeholder="e.g. ABC 123 GP" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">VIN Number</label>
                    <input type="text" name="vin_number" class="form-control" placeholder="Vehicle VIN" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Tank Description</label>
                    <textarea name="tank_description" class="form-control" rows="3" placeholder="Describe the tank" required></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Trailer Compartments</label>
                    <input type="number" name="trailer_compartments" class="form-control" placeholder="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Job Number</label>
                    <input type="number" name="job_number" class="form-control" placeholder="0" required>
                </div>
                <div class="col-md-4">
                    <!-- spacer for alignment -->
                </div>
                <div class="col-md-6">
                    <label class="form-label">Issue Date</label>
                    <input type="date" name="issue_date" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control" required>
                </div>
            </div>
            <hr style="margin:24px 0 16px; border-color:#eee;">
            <div class="d-flex justify-content-end gap-2">
                <a class="btn btn-outline-secondary" href="admin_dashboard.php">Cancel</a>
                <button class="btn btn-primary" type="submit" name="Submit"><i class="bi bi-check-lg me-1"></i>Add Certificate</button>
            </div>
        </form>
    </div>

</div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>