<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'conn.php';
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Validate certificate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid certificate ID.");
}

$id = (int) $_GET['id'];

// Fetch certificate data
$stmt = $conn->prepare("SELECT * FROM `certificate` WHERE certificate_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No certificate found.");
}

$row = $result->fetch_assoc();
$stmt->close();

// Convert images to base64 for embedding in PDF
$bgImagePath = __DIR__ . '/cert1.png';
$bgBase64 = '';
if (file_exists($bgImagePath)) {
    $bgBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($bgImagePath));
}

$watermarkPath = __DIR__ . '/WaterMark.png';
$watermarkBase64 = '';
if (file_exists($watermarkPath)) {
    $watermarkBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($watermarkPath));
}

// Format dates
$issue_date = date("d-m-Y", strtotime($row['issue_date']));
$expiry_date = date("d-m-Y", strtotime($row['expiry_date']));

// Build compartments row
$testedCompartments = (int) $row['trailer_compartments'];
$compartmentHeaders = '';
$compartmentCells = '';
for ($i = 1; $i <= 8; $i++) {
    $compartmentHeaders .= "<th style=\"border:1px solid #333; padding:4px 6px; text-align:center; background:#fff; font-size:16px; font-weight:bold;\">$i</th>";
    $val = ($i <= $testedCompartments) ? 'YES' : 'NO';
    $compartmentCells .= "<td style=\"border:1px solid #333; padding:4px 6px; text-align:center; background:#fff; font-size:16px;\">$val</td>";
}

// Escape output for HTML
$customer = htmlspecialchars($row['customer']);
$reg_number = htmlspecialchars($row['registration_number']);
$vin_number = htmlspecialchars($row['vin_number']);
$tank_desc = htmlspecialchars($row['tank_description']);
$trailer_comp = htmlspecialchars($row['trailer_compartments']);
$job_number = htmlspecialchars($row['job_number']);
$cert_id = htmlspecialchars($row['certificate_id']);

// Build the HTML for the PDF — layout matches the printed certificate exactly
$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page {
        size: A4 portrait;
        margin: 0;
    }
    body {
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
    }
    .page {
        width: 210mm;
        height: 297mm;
        position: relative;
        overflow: hidden;
    }
    .bg-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 210mm;
        height: 297mm;
        z-index: 0;
    }
    .watermark {
        position: absolute;
        bottom: 12mm;
        right: 25mm;
        width: 30mm;
        height: auto;
        z-index: 1;
    }
    .cert-number {
        position: absolute;
        top: 8mm;
        right: 12mm;
        color: red;
        font-weight: bold;
        font-size: 16px;
        z-index: 2;
    }
    .content {
        position: relative;
        z-index: 2;
        padding: 0 15mm;
    }
    /* Spacer for the logo area at top of background */
    .logo-spacer {
        height: 52mm;
    }
    .cert-title {
        text-align: center;
        font-size: 26px;
        color: #006644;
        font-weight: bold;
        margin: 0 0 4mm 0;
        letter-spacing: 1px;
    }
    .cert-intro {
        text-align: center;
        font-size: 16px;
        color: #999;
        margin: 0 10mm 5mm 10mm;
        line-height: 1.4;
        font-style: italic;
    }
    .customer-name {
        text-align: center;
        font-size: 22px;
        color: #002B63;
        margin: 0 0 5mm 0;
        font-style: italic;
    }
    /* Details table */
    .details-table {
        width: 85%;
        margin: 0 auto 4mm auto;
        border-collapse: collapse;
    }
    .details-table td {
        padding: 3px 10px;
        font-size: 16px;
        vertical-align: top;
    }
    .details-table td.label-col {
        width: 48%;
        color: #555;
    }
    .details-table td.value-col {
        color: #333;
    }
    .red-value {
        color: red !important;
    }
    /* Test section */
    .test-section-title {
        font-size: 17px;
        color: #006644;
        font-weight: bold;
        margin: 3mm 0 1mm 0;
    }
    .test-table {
        width: 85%;
        margin: 0 auto 4mm auto;
        border-collapse: collapse;
    }
    .test-table td {
        padding: 3px 10px;
        font-size: 16px;
        vertical-align: middle;
    }
    .test-table td.test-name {
        width: 48%;
        color: #555;
    }
    .test-table td.test-result {
        color: #333;
    }
    .check {
        color: green;
        font-size: 17px;
    }
    /* Compartments results table */
    .results-table {
        width: 85%;
        margin: 0 auto 5mm auto;
        border-collapse: collapse;
        border: 1px solid #333;
    }
    .results-table th, .results-table td {
        border: 1px solid #333;
        padding: 4px 6px;
        text-align: center;
        font-size: 16px;
        background: #fff;
    }
    .results-table th {
        font-weight: bold;
    }
    /* Terms section */
    .terms {
        width: 85%;
        margin: 3mm auto 0 auto;
        font-size: 15px;
        color: #777;
        line-height: 1.5;
        text-align: center;
    }
    .terms p {
        margin: 0 0 3mm 0;
    }
</style>
</head>
<body>
<div class="page">
    <!-- Background image -->
    <img class="bg-image" src="{$bgBase64}" />

    <!-- PASSED watermark stamp -->
    <img class="watermark" src="{$watermarkBase64}" />

    <!-- Cert number top right -->
    <div class="cert-number">Cert No. {$cert_id}</div>

    <div class="content">
        <!-- Space for logos in background -->
        <div class="logo-spacer"></div>

        <div class="cert-title">PRESSURE TEST CERTIFICATE</div>

        <div class="cert-intro">
            This certificate has been issued to confirm that the vehicle's tank has passed all the required tests and meets the specified criteria.
        </div>

        <div class="customer-name">{$customer}</div>

        <!-- Certificate details -->
        <table class="details-table">
            <tr>
                <td class="label-col">Registration Number</td>
                <td class="value-col">{$reg_number}</td>
            </tr>
            <tr>
                <td class="label-col">VIN Number</td>
                <td class="value-col">{$vin_number}</td>
            </tr>
            <tr>
                <td class="label-col">Tank Description</td>
                <td class="value-col">{$tank_desc}</td>
            </tr>
            <tr>
                <td class="label-col">Trailer Compartments</td>
                <td class="value-col">{$trailer_comp}</td>
            </tr>
            <tr>
                <td class="label-col">Job Number</td>
                <td class="value-col">{$job_number}</td>
            </tr>
            <tr>
                <td class="label-col">Issue Date</td>
                <td class="value-col red-value">{$issue_date}</td>
            </tr>
            <tr>
                <td class="label-col">Expiry Date</td>
                <td class="value-col red-value">{$expiry_date}</td>
            </tr>
        </table>

        <!-- Tests performed -->
        <table class="test-table">
            <tr>
                <td colspan="2" class="test-section-title">The following were tested</td>
            </tr>
            <tr>
                <td class="test-name">Degas</td>
                <td class="test-result"></td>
            </tr>
            <tr>
                <td class="test-name">Barrel</td>
                <td class="test-result">35KPa <span class="check">&#9745;</span></td>
            </tr>
            <tr>
                <td class="test-name">Compartments</td>
                <td class="test-result">35KPa <span class="check">&#9745;</span></td>
            </tr>
            <tr>
                <td class="test-name">Bottom Pipe</td>
                <td class="test-result">20KPa <span class="check">&#9745;</span></td>
            </tr>
            <tr>
                <td class="test-name">Offloading Pipe</td>
                <td class="test-result">800KPa <span class="check">&#9745;</span></td>
            </tr>
            <tr>
                <td class="test-name">Valance</td>
                <td class="test-result">35KPa <span class="check">&#9745;</span></td>
            </tr>
        </table>

        <!-- Compartment results grid -->
        <table class="results-table">
            <thead>
                <tr>
                    <th style="border:1px solid #333; padding:4px 6px; text-align:center; background:#fff; font-size:11px; font-weight:bold;">Compartment(s)</th>
                    {$compartmentHeaders}
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="border:1px solid #333; padding:4px 6px; text-align:center; background:#fff; font-size:11px; font-weight:bold;">Tested</th>
                    {$compartmentCells}
                </tr>
            </tbody>
        </table>

        <!-- Terms and conditions -->
        <div class="terms">
            <p>
                This pressure certificate is valid for a period of <span style="color:red;">36 months (3 years)</span> unless any defects or malfunctions on the equipment are detected or whichever may occur first.
            </p>
            <p>
                Therefore Chato Electrical and Tankers Equipment Ltd will not accept any damages which may occur due to the ware and tear in due course. It is the owners responsibility to ensure that the equipment is in good and safe working condition and that it is in compliance with all the laws or community by-laws.
            </p>
        </div>
    </div>
</div>
</body>
</html>
HTML;

// Configure dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');

try {
    $dompdf->render();
} catch (Exception $e) {
    error_log("PDF generation error: " . $e->getMessage());
    die("Error generating PDF. Please contact the administrator.");
}

// Generate filename
$filename = 'Certificate_' . $row['certificate_id'] . '_' . $row['registration_number'] . '.pdf';
$filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);

// Stream to browser
$dompdf->stream($filename, ['Attach' => true]);
