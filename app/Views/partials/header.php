<?php
// Get user role from session (default to 'guest' if not set)
$userRole = session()->get('user_role') ?? 'guest';
$userName = session()->get('user_name') ?? 'User';

// Set navbar color and badge based on role
if ($userRole === 'admin') {
    $navbarColor = 'bg-primary';  // Blue for admin
    $badgeText = 'Administrator';
    $badgeIcon = 'bi-shield-check';
    $dashboardUrl = base_url('admin/dashboard');
} elseif ($userRole === 'judge') {
    $navbarColor = 'bg-success';  // Green for judge
    $badgeText = 'Judge';
    $badgeIcon = 'bi-star';
    $dashboardUrl = base_url('judge/dashboard');
} else {
    $navbarColor = 'bg-secondary';  // Gray for guest
    $badgeText = 'Guest';
    $badgeIcon = 'bi-person';
    $dashboardUrl = base_url('/');
}
?>

<!-- Top Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark <?= $navbarColor ?> sticky-top">
    <div class="container-fluid">
        <!-- Logo/Brand -->
        <a class="navbar-brand" href="<?= $dashboardUrl ?>">
            <?php if (system_logo()): ?>
                <img src="<?= system_logo() ?>" alt="Logo" class="navbar-brand-logo">
            <?php else: ?>
                <i class="bi bi-trophy"></i>
            <?php endif; ?>
            <?= esc(system_name()) ?>
        </a>
        
        <!-- Toggle button for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navbar items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Role Badge -->
                <?php if ($userRole !== 'guest'): ?>
                <li class="nav-item">
                    <span class="nav-link">
                        <span class="badge bg-warning text-dark">
                            <i class="<?= $badgeIcon ?>"></i> <?= $badgeText ?>
                        </span>
                    </span>
                </li>
                <?php endif; ?>
                
                <!-- User Info -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user-info" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            <?= strtoupper(substr($userName, 0, 1)) ?>
                        </div>
                        <span><?= esc($userName) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('admin/settings') ?>"><i class="bi bi-gear"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
