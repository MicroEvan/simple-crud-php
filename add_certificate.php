<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not logged in or not an admin
    exit();
}

require_once 'conn.php';

define('FIELD_REQUIRED_MESSAGE', '<font color="red">Field is required.</font><br>');
define('DATA_ADDED_MESSAGE', '<font color="green">Data Added Successfully.</font><br>');

if (isset($_POST['Submit'])) {
    // Escape the input
    $customer = mysqli_real_escape_string($conn, $_POST['customer']);
    $registration_number = mysqli_real_escape_string($conn, $_POST['registration_number']);
    $vin_number = mysqli_real_escape_string($conn, $_POST['vin_number']);
    $tank_description = mysqli_real_escape_string($conn, $_POST['tank_description']);
    $trailer_compartments = mysqli_real_escape_string($conn, $_POST['trailer_compartments']);
    $job_number = mysqli_real_escape_string($conn, $_POST['job_number']);
    $issue_date = mysqli_real_escape_string($conn, $_POST['issue_date']);
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);

    // Check if the required fields are empty
    if (empty($customer) || empty($registration_number) || empty($vin_number) || empty($tank_description) || empty($trailer_compartments) || empty($job_number) || empty($issue_date) || empty($expiry_date)) {
        if (empty($customer)) {
            echo FIELD_REQUIRED_MESSAGE . "Customer Name is required.<br>";
        }
        if (empty($registration_number)) {
            echo FIELD_REQUIRED_MESSAGE . "Registration Number is required.<br>";
        }
        if (empty($vin_number)) {
            echo FIELD_REQUIRED_MESSAGE . "VIN Number is required.<br>";
        }
        if (empty($tank_description)) {
            echo FIELD_REQUIRED_MESSAGE . "Tank Description is required.<br>";
        }
        if (empty($trailer_compartments)) {
            echo FIELD_REQUIRED_MESSAGE . "Trailer Compartments is required.<br>";
        }
        if (empty($job_number)) {
            echo FIELD_REQUIRED_MESSAGE . "Job Number is required.<br>";
        }
        if (empty($issue_date)) {
            echo FIELD_REQUIRED_MESSAGE . "Issue Date is required.<br>";
        }
        if (empty($expiry_date)) {
            echo FIELD_REQUIRED_MESSAGE . "Expiry Date is required.<br>";
        }

        echo '<br><a href="javascript:self.history.back();">Back</a>';
    } else {
        // Prepare the SQL statement for insertion
        $stmt = $conn->prepare("INSERT INTO `certificate` (customer, registration_number, vin_number, tank_description, trailer_compartments, job_number, issue_date, expiry_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind parameters to the prepared statement
        $stmt->bind_param("sssssiss", $customer, $registration_number, $vin_number, $tank_description, $trailer_compartments, $job_number, $issue_date, $expiry_date); // 's' for string, 'i' for integer

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo DATA_ADDED_MESSAGE;
            // Close the statement
            $stmt->close();
            echo '<br><a href="index.php">View Certificates</a>';
        } else {
            echo "Error adding record: " . $stmt->error;
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
                    <tr>
                        <td>
                            <div>
                                <button class="btn btn-primary" type="submit" name="Submit">Add Certificate</button>
                                <a class="btn btn-secondary" href="admin_dashboard.php">Cancel</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>