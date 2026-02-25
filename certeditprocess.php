<?php
include 'conn.php';
session_start();

if (isset($_POST['update'])) {
    $certificate_id = trim($_POST['certificate_id'] ?? '');
    $customer = trim($_POST['customer'] ?? '');
    $registration_number = trim($_POST['registration_number'] ?? '');
    $vin_number = trim($_POST['vin_number'] ?? '');
    $tank_description = trim($_POST['tank_description'] ?? '');
    $trailer_compartments = trim($_POST['trailer_compartments'] ?? '');
    $job_number = trim($_POST['job_number'] ?? '');
    $expiry_date = trim($_POST['expiry_date'] ?? '');

    $errors = [];

    // Validate required fields
    if (empty($certificate_id)) $errors[] = "Certificate ID is missing.";
    if (empty($customer)) $errors[] = "Customer Name is required.";
    if (empty($registration_number)) $errors[] = "Registration Number is required.";
    if (empty($vin_number)) $errors[] = "VIN Number is required.";
    if (empty($tank_description)) $errors[] = "Tank Description is required.";
    if (empty($trailer_compartments)) $errors[] = "Trailer Compartments is required.";
    if (empty($job_number)) $errors[] = "Job Number is required.";
    if (empty($expiry_date)) {
        $errors[] = "Expiry Date is required.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $expiry_date)) {
        $errors[] = "Invalid date format. Please use YYYY-MM-DD.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: edit_certificate.php?id=" . urlencode($certificate_id));
        exit();
    }

    try {
        $stmt = $conn->prepare("UPDATE `certificate` SET customer=?, registration_number=?, vin_number=?,
            tank_description=?, trailer_compartments=?, job_number=?, expiry_date=? WHERE certificate_id=?");

        $stmt->bind_param("ssssissi", $customer, $registration_number, $vin_number,
            $tank_description, $trailer_compartments, $job_number, $expiry_date, $certificate_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Certificate updated successfully.";
            $stmt->close();
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $_SESSION['errors'] = ["Could not update the certificate. Please try again."];
            header("Location: edit_certificate.php?id=" . urlencode($certificate_id));
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        error_log("SQL Error in certeditprocess.php: " . $e->getMessage());
        $_SESSION['errors'] = ["Something went wrong while updating the certificate. Please contact the administrator."];
        header("Location: edit_certificate.php?id=" . urlencode($certificate_id));
        exit();
    } catch (Exception $e) {
        error_log("Error in certeditprocess.php: " . $e->getMessage());
        $_SESSION['errors'] = ["An unexpected error occurred. Please try again later."];
        header("Location: edit_certificate.php?id=" . urlencode($certificate_id));
        exit();
    }
}
?>
