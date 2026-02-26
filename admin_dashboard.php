<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login"); // Redirect to login if not logged in or not an admin
    exit();
}

// Include the database connection
include 'conn.php';

// Pagination variables
$records_per_page = 10; // Number of records to display per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $records_per_page; // Offset for SQL query

// Search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Fetch total number of records with optional search
$total_result = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM certificate 
    WHERE customer LIKE '%$search%' OR registration_number LIKE '%$search%' OR vin_number LIKE '%$search%'
");
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $records_per_page); // Total number of pages

// Fetch certificates for the current page with optional search
$result = mysqli_query($conn, "
    SELECT *
    FROM certificate
    WHERE customer LIKE '%$search%' OR registration_number LIKE '%$search%' OR vin_number LIKE '%$search%'
    ORDER BY certificate_id DESC
    LIMIT $offset, $records_per_page
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/'; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Dashboard - Chato Certificates</title>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="content-wrapper">
<div class="container">

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="bi bi-speedometer2 me-2" style="color:var(--primary)"></i>Dashboard</h2>
            <p style="color:#777; font-size:14px; margin:4px 0 0;">Welcome back, <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
        </div>
        <a href="certificates/add" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Certificate</a>
    </div>

    <!-- Search Card -->
    <div class="card-chato" style="padding:16px 24px;">
        <form method="GET" class="search-bar" style="max-width:100%;">
            <div class="input-group">
                <span class="input-group-text" style="border-radius:12px 0 0 12px; background:#f8f9fa; border:1px solid #ddd; border-right:none;">
                    <i class="bi bi-search" style="color:#999"></i>
                </span>
                <input type="text" name="search" class="form-control" style="border-radius:0 12px 12px 0; border-left:none;" placeholder="Search by Customer, Reg No, or VIN..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <button class="btn btn-primary" type="submit" style="white-space:nowrap;"><i class="bi bi-search me-1"></i>Search</button>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="card-chato" style="padding:0; overflow:hidden;">
        <div class="table-responsive">
        <table class="table-chato">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Registration No.</th>
                    <th>VIN Number</th>
                    <th>Expiry Date</th>
                    <th>Job No.</th>
                    <th style="text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['certificate_id']; ?></td>
                    <td style="font-weight:500;"><?php echo htmlspecialchars($row['customer']); ?></td>
                    <td><?php echo htmlspecialchars($row['registration_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['vin_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['expiry_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['job_number']); ?></td>
                    <td style="text-align:center;">
                        <div class="btn-group">
                            <a href="certificates/view?id=<?php echo $row['certificate_id']; ?>" class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                            <a href="certificates/edit?id=<?php echo $row['certificate_id']; ?>" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil-square"></i></a>
                            <a href="certificates/delete?id=<?php echo $row['certificate_id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this certificate?');"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>"><i class="bi bi-chevron-left"></i></a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>"><i class="bi bi-chevron-right"></i></a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>

</div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
