<?php
// Get user role from session
$userRole = session()->get('user_role') ?? 'guest';
$currentUrl = current_url();

// Don't show sidebar for judges
if ($userRole === 'judge') {
    return;
}
?>

<!-- Sidebar -->
<div class="col-md-3 col-lg-2 px-0">
    <div class="sidebar">
        
        <?php if ($userRole === 'admin'): ?>
            <!-- ADMIN SIDEBAR -->
            <div class="sidebar-header text-center">
                <?php if (system_logo()): ?>
                    <img src="<?= system_logo() ?>" alt="Logo" style="height:48px; width:auto; border-radius:6px; margin-bottom:8px; background:#fff; padding:4px;">
                <?php else: ?>
                    <div class="crown-icon" style="font-size:28px;">👑</div>
                <?php endif; ?>
                <h4 style="margin:6px 0 0;"><?= esc(system_name()) ?></h4>
                <p>Welcome, System Administrator</p>
            </div>
            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= ($currentUrl == base_url('admin/dashboard')) ? 'active' : '' ?>" 
                       href="<?= base_url('admin/dashboard') ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= ($currentUrl == base_url('admin/contestants')) ? 'active' : '' ?>" 
                       href="<?= base_url('admin/contestants') ?>">
                        <i class="bi bi-people"></i> Contestants
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= ($currentUrl == base_url('admin/judges')) ? 'active' : '' ?>" 
                       href="<?= base_url('admin/judges') ?>">
                        <i class="bi bi-person-badge"></i> Judges
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= ($currentUrl == base_url('admin/rounds-criteria')) ? 'active' : '' ?>" 
                       href="<?= base_url('admin/rounds-criteria') ?>">
                        <i class="bi bi-diagram-3"></i> Rounds & Criteria
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= ($currentUrl == base_url('admin/results')) ? 'active' : '' ?>" 
                       href="<?= base_url('admin/results') ?>">
                        <i class="bi bi-bar-chart"></i> Results & Rankings
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= ($currentUrl == base_url('admin/settings')) ? 'active' : '' ?>" 
                       href="<?= base_url('admin/settings') ?>">
                        <i class="bi bi-gear-fill"></i> Settings
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('logout') ?>">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
            
        <?php elseif ($userRole === 'judge'): ?>
            <!-- JUDGE SIDEBAR -->
            <div class="sidebar-title">
                <i class="bi bi-star"></i> Judge Menu
            </div>
            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= ($currentUrl == base_url('judge/dashboard')) ? 'active' : '' ?>" 
                       href="<?= base_url('judge/dashboard') ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= ($currentUrl == base_url('judge/contestants')) ? 'active' : '' ?>" 
                       href="<?= base_url('judge/contestants') ?>">
                        <i class="bi bi-people"></i> Score Contestants
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= ($currentUrl == base_url('judge/submit-score')) ? 'active' : '' ?>" 
                       href="<?= base_url('judge/submit-score') ?>">
                        <i class="bi bi-pencil-square"></i> Submit Score
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= ($currentUrl == base_url('judge/history')) ? 'active' : '' ?>" 
                       href="<?= base_url('judge/history') ?>">
                        <i class="bi bi-clock-history"></i> History
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('logout') ?>">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
            
        <?php else: ?>
            <!-- GUEST/DEFAULT SIDEBAR -->
            <div class="sidebar-title">
                <i class="bi bi-info-circle"></i> Menu
            </div>
            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/') ?>">
                        <i class="bi bi-house"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                </li>
            </ul>
        <?php endif; ?>
        
    </div>
</div>
