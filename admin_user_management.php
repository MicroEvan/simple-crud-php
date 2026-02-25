<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not logged in or not an admin
    exit();
}

// Include the database connection
include 'conn.php';

// Initialize variables
$users = [];
$message = '';

// Fetch all users from the database
$result = $conn->query("SELECT * FROM user");
if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle add/edit/delete user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $role = $_POST['role'];

        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO user (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        if ($stmt->execute()) {
            $message = "User added successfully.";
        } else {
            $message = "Error adding user: " . $stmt->error;
        }
    } elseif (isset($_POST['edit_user'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        // Prepare the update statement
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE user SET name = ?, email = ?, password = ?, role = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $email, $hashed_password, $role, $id);
        } else {
            $stmt = $conn->prepare("UPDATE user SET name = ?, email = ?, role = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $role, $id);
        }

        if ($stmt->execute()) {
            $message = "User updated successfully.";
        } else {
            $message = "Error updating user: " . $stmt->error;
        }
    } elseif (isset($_POST['delete_user'])) {
        $id = $_POST['id'];

        // Delete user
        $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "User deleted successfully.";
        } else {
            $message = "Error deleting user: " . $stmt->error;
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
    <title>User Management</title>
</head>
<body>
<div class="container">
    <?php include 'nav.php'; ?>
</div>
<div class="container">

    <h2 class="mt-5">User Management</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Add User Button to open the modal -->
    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>

    <h3 class="mt-5">Existing Users</h3>
    <div class="table-responsive">
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <!-- Edit User Button -->
                            <button class="btn btn-warning btn-sm" title="Edit" onclick="editUser(<?php echo $user['id']; ?>, '<?php echo $user['name']; ?>', '<?php echo $user['email']; ?>', '<?php echo $user['role']; ?>')"><i class="bi bi-pencil-square"></i></button>

                            <!-- Delete User Button -->
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="delete_user" class="btn btn-danger btn-sm" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <div class="container">
    <?php
        include 'approve_users.php'
    ?>
    </div>



    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="POST" action="">
                        <input type="hidden" name="id" id="editUserId">
                        <div class="mb-3">
                            <label for="editUserName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editUserName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editUserEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserPassword" class="form-label">New Password (leave blank to keep current password)</label>
                            <input type="password" class="form-control" id="editUserPassword" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="editUserRole" class="form-label">Role</label>
                            <select class="form-select" id="editUserRole" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" name="edit_user" class="btn btn-primary">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function editUser(id, name, email, role) {
        document.getElementById('editUserId').value = id;
        document.getElementById('editUserName').value = name;
        document.getElementById('editUserEmail').value = email;
        document.getElementById('editUserRole').value = role;
        document.getElementById('editUserPassword').value = ''; // Clear password field
        var editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        editUserModal.show();
    }
</script>
</body>
</html>
