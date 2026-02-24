<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not logged in or not an admin
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard</title>
</head>
<body>
<div class="container">
    <?php include 'nav.php'; ?>
</div>
<div class="container">
    <h2 class="mt-5">Dashboard</h2>
    <p>Welcome, <?php echo $_SESSION['user_email']; ?>! You have admin access.</p>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="add_certificate.php" class="btn btn-primary">New</a>

        <form method="GET" class="d-flex" style="width: 400px;">
            <input type="text" name="search" class="form-control" placeholder="Search by Customer, Registration Number, or VIN Number" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-secondary ms-2" type="submit">Search</button>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Registration Number</th>
                <th>VIN Number</th>
                <th>Expiry Date</th>
                <th>Job Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Fetching data from the certificate table in the database
        while($row = mysqli_fetch_assoc($result)) {
            echo "
            <tr>
                <td>{$row['certificate_id']}</td>
                <td>{$row['customer']}</td>
                <td>{$row['registration_number']}</td>
                <td>{$row['vin_number']}</td>
                <td>{$row['expiry_date']}</td>
                <td>{$row['job_number']}</td>
                <td>
                    <div class='btn-group'>
                        <button type='button' class='btn btn-outline-secondary'>Choose</button>
                        <button type='button' class='btn btn-outline-secondary dropdown-toggle dropdown-toggle-split' data-bs-toggle='dropdown' aria-expanded='false'>
                            <span class='visually-hidden'>Toggle Dropdown</span>
                        </button>
                        <ul class='dropdown-menu dropdown-menu-end'>
                            <li><a class='dropdown-item' href='view_certificate.php?id={$row['certificate_id']}'>View</a></li>
                            <li><a class='dropdown-item' href='edit_certificate.php?id={$row['certificate_id']}'>Edit</a></li>
                            <li><a class='dropdown-item' href='delete_certificate.php?id={$row['certificate_id']}'>Delete</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            ";
        }
        ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" tabindex="-1">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<footer>
    <?php include 'footer.php'; ?>
</footer>
</html>
