<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not logged in or not an admin
    exit();
}

require_once 'conn.php';

// Getting certificate_id from URL
$id = $_GET['id'];

// Query to fetch certificate data by certificate_id
$query = "SELECT * FROM `certificate` WHERE certificate_id = $id";

$result = mysqli_query($conn, $query);

// If no record is found, show an error
if (!$result || mysqli_num_rows($result) == 0) {
    echo "No certificate found.";
    exit();
}

$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>View Certificate</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            /*background-color: #f4f4f4;*/
            margin: 10px;
            padding: 0;
        }
        .certificate-container {
            width: 210mm; /* A4 width */
            height: 297mm; /* A4 height */
            margin: auto;
            padding: 15px;
            position: relative;
            background-image: url(cert1.png);
            background-size: 100% 100%;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center tables horizontally */
            justify-content: flex-start; /* Align items to the top */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .customer-name {
            /*text-align: center;*/
            font-size: 25px;
            color: #002B63;
            margin-bottom: 20px;
        }
        .certificate-test {
            text-align: left;
            font-size: 15px;
            color: #002B63;
        }

        table {
            width: 80%; /* Make tables occupy 80% of the container width */
            margin-bottom: 20px; /* Space between tables */
            border-collapse: collapse;
            border-radius: 5px; /* Rounded corners */
            overflow: hidden; /* Prevents overflow from rounded corners */
            /*box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);*/
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .results {
            border: 1px solid black; /* Light border for tables */
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent background for better visibility */
        }
        .results th, .results td {
    border: 1px solid black; /* Border for table cells */
    padding: 8px; /* Add some padding */
    text-align: center; /* Center text for better presentation */
    background: white; /* Optional: solid background for cells */
}
        .cert-name {
            display: flex;
            justify-content: center; /* Center horizontally */
            text-align: center; /* Align text in the center */
        }
        .certification p{
            font-size: 16px;
            color: #777;
            margin-bottom: 20px;
            padding: 0 20px; /* Adjust the left and right padding as needed */
            text-align: center; /* Justifies the text */
        }
        .certification-terms {
            display: flex;
            justify-content: center; /* Center horizontally */
            text-align: justify; /* Align text in the center */
            padding: 0 20px; /* Adjust the left and right padding as needed */
            
        }
        .certificate-footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
            display: flex;
            justify-content: center; /* Center horizontally */
            text-align: center; /* Align text in the center */
        }
        .print-button {
            margin-top: 20px;
        }
        .print-button input {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .print-button input:hover {
            background-color: #45a049;
        }
        /* Responsive wrapper to scale certificate on small screens */
        .certificate-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 10px 0;
        }
        .certificate-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            padding: 20px 0;
            flex-wrap: wrap;
        }
        @media screen and (max-width: 800px) {
            .certificate-container {
                transform: scale(0.6);
                transform-origin: top center;
                margin-bottom: -120mm;
            }
        }
        @media screen and (min-width: 801px) and (max-width: 1024px) {
            .certificate-container {
                transform: scale(0.8);
                transform-origin: top center;
                margin-bottom: -60mm;
            }
        }
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .certificate-wrapper {
                overflow: visible;
            }
            .certificate-container {
                width: 210mm;
                height: 297mm;
                box-shadow: none;
                border-radius: 0;
                transform: none !important;
                margin: 0 !important;
            }
            nav,
            .print-button,
            .certificate-actions,
            .certificate-footer {
                display: none;
            }
        }
    </style>
</head>
<body>
<nav>
<div class="container">
    <?php
    include 'nav.php';
    ?>
</div>
</nav>

<div class="certificate-wrapper">
<div class="certificate-container">

    <span style="color:red; padding: 10px; position:absolute; right: 35px; top: 10px; font-weight: bold;">Cert No. <?php echo $row['certificate_id']; ?></span>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>


    <div class="cert-name">
        <article>
            <h1 style="font-size: 30px; color: #002B63; font-weight: bold; margin-bottom: 20px;">PRESSURE TEST CERTIFICATE</h1>
            <div class="certification">
            <p >This certificate has been issued to confirm that the vehicle's tank has passed all the required tests and meets the specified criteria.</p>
            </div>
        </article>
    </div>

    <span class="customer-name"><?php echo $row['customer']; ?></span>
    <!-- Customer details table -->
    <table style="border-collapse: collapse;  line-height: 0; border-spacing: 0;"><br>
        <tbody>
            <tr>
                <td>Registration Number</td>
                <td><?php echo $row['registration_number']; ?></td>
            </tr>
            <tr>
                <td>VIN Number</td>
                <td><?php echo $row['vin_number']; ?></td>
            </tr>
            <tr>
                <td>Tank Description</td>
                <td><?php echo $row['tank_description']; ?></td>
            </tr>
            <tr>
                <td>Trailer Compartments</td>
                <td><?php echo $row['trailer_compartments']; ?></td>
            </tr>
            <tr>
                <td>Job Number</td>
                <td><?php echo $row['job_number']; ?></td>
            </tr>
            <tr>
                <td>Issue Date</td>
                <td style="color:red;">
                    <?php
                        $formatted_date = date("d-m-Y", strtotime($row['issue_date']));
                        echo $formatted_date;
                    ?>
                </td>
            </tr>
            <tr>
                <td>Expiry Date</td>
                <td style="color:red;">
                    <?php
                        $formatted_date = date("d-m-Y", strtotime($row['expiry_date']));
                        echo $formatted_date;
                    ?>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Test details table -->
    <table style="border-collapse: collapse;  line-height: 0; border-spacing: 0;">
        <thead>
            <tr>
                <th colspan="3" class="certificate-test">The following were tested</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Degas</td>

            </tr>
            <tr>
                <td>Barrel</td>
                <td>35KPa <i class="fas fa-check-square" style="color: green;"></i> <!-- Checked icon --></td>
                
            </tr>
            <tr>
                <td>Compartments</td>
                <td>35KPa <i class="fas fa-check-square" style="color: green;"></i> <!-- Checked icon --></td>
                
            </tr>
            <tr>
                <td>Bottom Pipe</td>
                <td>20KPa <i class="fas fa-check-square" style="color: green;"></i> <!-- Checked icon --></td>
                
            </tr>
            <tr>
                <td>Offloading Pipe</td>
                <td>800KPa <i class="fas fa-check-square" style="color: green;"></i> <!-- Checked icon --></td>
                
            </tr>
            <tr>
                <td>Valance</td>
                <td>35KPa <i class="fas fa-check-square" style="color: green;"></i> <!-- Checked icon --></td>
            </tr>
            <!-- Add more rows as needed -->
        </tbody>
    </table>
<!-- Results details table -->
<table class="results" style="border-collapse: collapse; border: 1px solid black; line-height: 1;">
    <thead>
        <tr>
            <th>Compartment(s)</th>
            <?php for ($i = 1; $i <= 8; $i++) : ?>
                <th><?php echo $i; ?></th>
            <?php endfor; ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>Tested</th>
            <?php
            // Retrieve the number of compartments tested from the database
            $testedCompartments = $row['trailer_compartments'];

            // Loop through each compartment, marking "YES" for tested compartments and "NO" for the rest
            for ($i = 1; $i <= 8; $i++) {
                if ($i <= $testedCompartments) {
                    echo "<td>YES</td>";
                } else {
                    echo "<td>NO</td>";
                }
            }
            ?>
        </tr>
    </tbody>
</table>

<div class="certification-terms">
        <article>
            <p style="font-size: 16px; color: #777; margin-bottom: 20px;">
            This pressure certificate is valid for a period of <span style="color: red;">36 months (3 years)</span> unless any defects or malfunctions on the equipment are detected or whichever may occur first.
            </p>
            <p style="font-size: 16px; color: #777; margin-bottom: 20px;">
                Therefore Chato Electrical and Tankers Equipment Ltd will not accept any damages which may occur due to the ware and tear in due course. It is the owners respomsibility to ensure that the equipment is in good and safe working condition and that it is in compliance with all the laws or community by-laws.
            </p>
        </article>
</div>

</div>
</div>

<!-- Buttons below the certificate -->
<div class="certificate-actions">
    <a href="admin_dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
    <a href="edit_certificate.php?id=<?php echo $row['certificate_id']; ?>" class="btn btn-secondary">Edit Certificate</a>
    <a href="generate_pdf.php?id=<?php echo $row['certificate_id']; ?>" class="btn btn-success">Download PDF</a>
    <button id="printpagebutton" type="button" onclick="window.print();" class="btn btn-primary">Print Certificate</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

