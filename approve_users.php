<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login");
    exit();
}

include 'conn.php';

$result = $conn->query("SELECT * FROM user WHERE status = 'inactive'");

?>
<!-- Pending Approvals Card -->
<div class="card-chato" style="padding:0; overflow:hidden; margin-top:8px;">
    <div style="padding:18px 24px; border-bottom:1px solid #eee;">
        <h5 style="margin:0; font-weight:600; color:#333; font-size:16px;"><i class="bi bi-clock-history me-2" style="color:#ffc107"></i>Pending User Approvals</h5>
    </div>
    <div class="table-responsive">
    <table class="table-chato">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th style="text-align:center;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows === 0): ?>
                <tr><td colspan="3" style="text-align:center; color:#999; padding:20px;">No pending approvals</td></tr>
            <?php else: ?>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td style="font-weight:500;"><?php echo htmlspecialchars($user['name']); ?></td>
                        <td style="text-align:center;">
                            <form action="users/approve" method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg me-1"></i>Approve</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
