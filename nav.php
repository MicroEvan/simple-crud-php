<nav class="navbar navbar-expand-lg navbar-chato">
  <div class="container">
    <!-- Brand -->
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo ($_SESSION['user_role'] === 'admin') ? 'dashboard' : 'dashboard'; ?>">
      <img src="logo.ico" alt="Logo" style="height:28px;width:28px;"> Chato Certificates
    </a>
    
    <!-- Toggler for small screens -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Navbar Links -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto gap-1">
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link" href="dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="users"><i class="bi bi-people me-1"></i>Users</a>
            </li>
            <?php endif; ?>

            <!-- Profile Link -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'Profile'); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="profile"><i class="bi bi-person me-2"></i>View Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout"><i class="bi bi-box-arrow-right me-2"></i>Log out</a></li>
                </ul>
            </li>
        </ul>
    </div>
  </div>
</nav>
