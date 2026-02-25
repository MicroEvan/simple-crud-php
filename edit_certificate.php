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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Edit Certificate</title>
</head>
<body>
    <div class="container mt-5">
    <a href="admin_dashboard.php">Home</a>
    <br><br>

    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-1">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form method="post" name="edit-form" action="certeditprocess.php">
        <table class="table">
            <tr>
                <td>Customer:</td>
                <td><input type="text" name="customer" value="<?php echo htmlspecialchars($customer); ?>"></td>
            </tr>
            <tr>
                <td>Registration Number:</td>
                <td><input type="text" name="registration_number" value="<?php echo htmlspecialchars($registration_number); ?>"></td>
            </tr>
            <tr>
                <td>VIN Number:</td>
                <td><input type="text" name="vin_number" value="<?php echo htmlspecialchars($vin_number); ?>"></td>
            </tr>
            <tr>
                <td>Tank Description:</td>
                <td><textarea name="tank_description"><?php echo htmlspecialchars($tank_description); ?></textarea></td>
            </tr>
            <tr>
                <td>Trailer Compartments:</td>
                <td><input type="number" name="trailer_compartments" value="<?php echo htmlspecialchars($trailer_compartments); ?>"></td>
            </tr>
            <tr>
                <td>Job Number:</td>
                <td><input type="number" name="job_number" value="<?php echo htmlspecialchars($job_number); ?>"></td>
            </tr>
            <tr>
                <td>Issue Date:</td>
                <td><input type="date" name="issue_date" value="<?php echo htmlspecialchars($issue_date); ?>"></td>
            </tr>
            <tr>
                <td>Expiry Date:</td>
                <td><input type="date" name="expiry_date" value="<?php echo htmlspecialchars($expiry_date); ?>"></td>
            </tr>
            <tr>
                <td><input type="hidden" name="certificate_id" value="<?php echo $certificate_id; ?>"></td>
                <td>
                    <input class="btn btn-primary" type="submit" name="update" value="Update">
                    <a class="btn btn-secondary" href="index.php">Cancel</a>
                </td>
            </tr>
        </table>
    </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
