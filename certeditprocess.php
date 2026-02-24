<?php
include 'conn.php';

define('FIELD_REQUIRED_MESSAGE', '<span style="color:red;">Field is required.</span><br>');
define('DATA_UPDATED_MESSAGE', '<span style="color:green;">Data Updated Successfully.</span><br>');

if (isset($_POST['update'])) {
    $certificate_id = mysqli_real_escape_string($conn, $_POST['certificate_id']);
    $customer = mysqli_real_escape_string($conn, $_POST['customer']);
    $registration_number = mysqli_real_escape_string($conn, $_POST['registration_number']);
    $vin_number = mysqli_real_escape_string($conn, $_POST['vin_number']);
    $tank_description = mysqli_real_escape_string($conn, $_POST['tank_description']);
    $trailer_compartments = mysqli_real_escape_string($conn, $_POST['trailer_compartments']);
    $job_number = mysqli_real_escape_string($conn, $_POST['job_number']);
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);

    // Validate the date format
    $date_regex = '/^\d{4}-\d{2}-\d{2}$/';
    if (!preg_match($date_regex, $expiry_date)) {
        echo '<span style="color:red;">Invalid date format. Please use YYYY-MM-DD.</span><br>';
    } else {
        $stmt = $conn->prepare("UPDATE `certificate` SET customer=?, registration_number=?, vin_number=?,
            tank_description=?, trailer_compartments=?, job_number=?, expiry_date=? WHERE certificate_id=?");

        // Bind 's' for strings and 'i' for integers
        $stmt->bind_param("ssssissi", $customer, $registration_number, $vin_number,
            $tank_description, $trailer_compartments, $job_number, $expiry_date, $certificate_id);

        if ($stmt->execute()) {
            echo DATA_UPDATED_MESSAGE;
            header("Location: index.php");
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
