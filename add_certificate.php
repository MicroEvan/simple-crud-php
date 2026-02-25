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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Pressure Test Certificate</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following:</strong>
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
                <?php echo htmlspecialchars($success); ?>
                <a href="admin_dashboard.php" class="alert-link ms-2">Back to Dashboard</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="add_certificate.php" method="post" name="add-form">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Customer Name:</td>
                        <td><input type="text" name="customer" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td>Registration Number:</td>
                        <td><input type="text" name="registration_number" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td>VIN Number:</td>
                        <td><input type="text" name="vin_number" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td>Tank Description:</td>
                        <td><textarea name="tank_description" class="form-control" required></textarea></td>
                    </tr>
                    <tr>
                        <td>Trailer Compartments:</td>
                        <td><input type="number" name="trailer_compartments" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td>Job Number:</td>
                        <td><input type="number" name="job_number" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td>Issue Date:</td>
                        <td><input type="date" name="issue_date" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td>Expiry Date:</td>
                        <td><input type="date" name="expiry_date" class="form-control" required></td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-end gap-2 mb-4">
                <a class="btn btn-secondary" href="admin_dashboard.php">Cancel</a>
                <button class="btn btn-primary" type="submit" name="Submit">Add Certificate</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>