<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Judge Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-4">
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <?php 
        $settingsModel = new \App\Models\SettingsModel();
        $logo = $settingsModel->getSetting('logo');
        if ($logo): 
        ?>
            <img src="<?= base_url('uploads/settings/' . $logo) ?>" 
                 alt="Logo" 
                 class="judge-dashboard-logo">
        <?php endif; ?>
        <h1 class="h2 mb-0 title-font"><?= esc(system_name()) ?></h1>
    </div>
    <div>
        <a href="<?= base_url('logout') ?>" class="btn btn-secondary text-white">
            Logout
        </a>
    </div>
</div>

<!-- Welcome Alert -->
<?php if (!empty($active_round)): ?>
    <?php if ($eliminated_contestants > 0): ?>
        <div class="alert alert-warning alert-dismissible fade show mb-4 alert-with-divider alert-border-warning" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <strong>Heads up!</strong> <?= $eliminated_contestants ?> <?= $eliminated_contestants == 1 ? 'contestant has' : 'contestants have' ?> been eliminated in this round.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($completed_scores > 0): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4 alert-with-divider alert-border-success" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <strong>Great job, Judge!</strong> You have completed scoring for all <?= $completed_scores ?> <?= $completed_scores == 1 ? 'contestant' : 'contestants' ?> in this round.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-warning alert-dismissible fade show mb-4 alert-with-divider alert-border-warning" role="alert">
        <i class="bi bi-info-circle-fill"></i>
        <strong>Welcome back, Judge!</strong> There is currently no active round. Please wait for the admin to activate a round.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-icon beige">
                <i class="bi bi-person-x-fill"></i>
            </div>
            <div class="stats-content">
                <h3><?= $eliminated_contestants ?></h3>
                <p>Eliminated Contestants</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stats-content">
                <h3><?= $completed_scores ?></h3>
                <p>Completed Scores</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-icon blue">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stats-content">
                <h3><?= $total_contestants ?></h3>
                <p>Total Contestants</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-icon orange">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="stats-content">
                <h3><?= $average_score ?></h3>
                <p>Average Score</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions-box mb-4">
    <div class="quick-actions-header">Quick Actions</div>
    <div class="quick-actions-buttons">
        <a href="<?= base_url('judge/select-round') ?>" class="quick-action-btn primary">Start Scoring</a>
        <a href="<?= base_url('judge/contestants') ?>" class="quick-action-btn">View Contestants</a>
    </div>
</div>

<!-- Bottom Section -->
<div class="row g-3">
    <!-- Current Round -->
    <div class="col-md-6">
        <div class="section-card">
            <h5 class="mb-3"><i class="bi bi-trophy"></i> Current Round</h5>
            <?php if (!empty($active_round)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-circle-fill text-primary icon-display-lg"></i>
                    <h4 class="mt-3"><?= esc($active_round['round_name']) ?></h4>
                    <p class="text-muted"><?= esc($active_round['description'] ?? 'Round ' . $active_round['round_number']) ?></p>
                    <div class="mt-4">
                        <span class="badge bg-success fs-6">Active</span>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-info-circle empty-state-icon icon-display-lg"></i>
                    <h5 class="mt-3">No Active Round</h5>
                    <p>Wait for the admin to start a new round</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Current Leaderboard -->
    <div class="col-md-6">
        <div class="section-card">
            <h5 class="mb-3"><i class="bi bi-bar-chart-fill"></i> Current Leaderboard</h5>
            <?php if (!empty($current_leaderboard)): ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($current_leaderboard as $index => $contestant): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <span class="badge <?= $index === 0 ? 'bg-warning' : 'bg-secondary' ?> me-2"><?= $index + 1 ?></span>
                                <strong><?= esc($contestant['name']) ?></strong>
                            </div>
                            <span class="badge bg-primary"><?= round($contestant['avg_score'], 1) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-muted text-center py-3">
                    <i class="bi bi-info-circle"></i> No scores available yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>
