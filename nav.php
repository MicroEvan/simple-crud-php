<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <!-- Brand -->
    <a class="navbar-brand" href="<?php echo ($_SESSION['user_role'] === 'admin') ? 'admin_dashboard.php' : 'user_dashboard.php'; ?>">Home</a>
    
    <!-- Toggler for small screens -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Navbar Links -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">  <!-- Added ms-auto here -->
            <!-- Users Link with Dropdown, Visible Only to Admin -->
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link" href="admin_user_management.php">Manage Users</a>
            </li>
            <?php endif; ?>

            <!-- Profile Link -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Profile
                </a>
                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="profile.php">View Profile</a></li>
                    <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                </ul>
            </li>
        </ul>
    </div>
  </div>
</nav>
