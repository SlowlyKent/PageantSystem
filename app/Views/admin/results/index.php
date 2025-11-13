<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Results & Rankings<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .result-card {
        transition: all 0.3s;
        border-left: 4px solid transparent; /* color comes from theme.css */
    }

    .result-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }

    /* overall-card colors come from theme.css; keep only layout effects here */
    .overall-card {
        cursor: pointer;
    }

    .overall-card:hover {
        transform: scale(1.05);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-trophy"></i> Results & Rankings</h1>
</div>

<!-- Quick Action Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card overall-card" onclick="window.location.href='<?= base_url('admin/results/overall') ?>'">
            <div class="card-body text-center">
                <i class="bi bi-trophy-fill" style="font-size: 48px; color: #ffd700;"></i>
                <h5 class="mt-3">Overall Rankings</h5>
                <p class="text-muted mb-0">View cumulative rankings</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card result-card" style="border-left-color: #667eea;">
            <div class="card-body text-center">
                <i class="bi bi-people-fill" style="font-size: 48px; color: #667eea;"></i>
                <h5 class="mt-3"><?= count($leaderboard ?? []) ?></h5>
                <p class="text-muted mb-0">Contestants Scored</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card result-card" style="border-left-color: #28a745;">
            <div class="card-body text-center">
                <i class="bi bi-award-fill" style="font-size: 48px; color: #28a745;"></i>
                <h5 class="mt-3"><?= count($rounds ?? []) ?></h5>
                <p class="text-muted mb-0">Total Rounds</p>
            </div>
        </div>
    </div>
</div>

<!-- Round Details Section -->
<?php if (!empty($rounds)): ?>
<div class="section-card mt-4">
    <h5>All Rounds</h5>
    <hr>
    <div class="row">
        <?php foreach ($rounds as $round): ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card result-card" style="border-left-color: #667eea;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Round <?= $round['round_number'] ?></h6>
                                <p class="text-muted small mb-2"><?= esc($round['round_name']) ?></p>
                                <span class="badge bg-<?= $round['status'] == 'active' ? 'success' : ($round['status'] == 'completed' ? 'secondary' : 'warning') ?>">
                                    <?= ucfirst($round['status']) ?>
                                </span>
                            </div>
                            <i class="bi bi-award" style="font-size: 32px; color: #667eea; opacity: 0.3;"></i>
                        </div>
                        <div class="mt-3">
                            <a href="<?= base_url('admin/results/round/' . $round['id']) ?>" 
                               class="btn btn-sm btn-primary w-100">
                                <i class="bi bi-eye"></i> View Rankings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
