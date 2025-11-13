<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
        <p>Welcome, System Administrator</p>
    </div>
</div>

<!-- Success/Error Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistics (Enhanced Theme-Aware Cards) -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-box p-4 d-flex align-items-center gap-3">
            <div class="icon-circle">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="flex-grow-1">
                <div class="value"><?= $total_contestants ?? 0 ?></div>
                <div class="label">Total Contestants</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-box p-4 d-flex align-items-center gap-3">
            <div class="icon-circle">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="flex-grow-1">
                <div class="value"><?= $active_judges ?? 0 ?></div>
                <div class="label">Active Judges</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-box p-4 d-flex align-items-center gap-3">
            <div class="icon-circle">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div class="flex-grow-1">
                <div class="value"><?= $total_rounds ?? 0 ?></div>
                <div class="label">Total Rounds</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-box p-4 d-flex align-items-center gap-3">
            <div class="icon-circle">
                <i class="bi bi-star-fill"></i>
            </div>
            <div class="flex-grow-1">
                <div class="value"><?= $scores_submitted ?? 0 ?></div>
                <div class="label">Scores Submitted</div>
            </div>
        </div>
    </div>
</div>

<!-- Content Sections (Enhanced Theme-Aware) -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card section-card">
            <div class="card-header bg-primary">
                <h5 class="mb-0 text-white"><i class="bi bi-flag-fill me-2"></i> Current Round</h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($current_round)): ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small">Round Name</span>
                            <strong><?= esc($current_round['round_name'] ?? ('Round ' . ($current_round['round_number'] ?? $current_round['id'] ?? ''))) ?></strong>
                        </div>
                        <hr class="my-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Status</span>
                            <?php 
                            $status = strtolower($current_round['status'] ?? 'pending');
                            $statusClass = $status === 'active' ? 'round-status-active' : ($status === 'completed' ? 'round-status-completed' : 'round-status-pending');
                            ?>
                            <span class="round-status-badge <?= $statusClass ?>">
                                <i class="bi bi-<?= $status === 'active' ? 'play-circle' : ($status === 'completed' ? 'check-circle' : 'clock') ?>-fill me-1"></i>
                                <?= esc(ucfirst($status)) ?>
                            </span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #d1d5db;"></i>
                        <p class="text-muted mt-3 mb-0">No active or completed round yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card section-card">
            <div class="card-header bg-primary">
                <h5 class="mb-0 text-white"><i class="bi bi-clipboard-check me-2"></i> Judge Completion</h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($current_round)): ?>
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <strong class="fs-5"><?= $judge_completion['completed'] ?>/<?= $judge_completion['total'] ?> Judges</strong>
                            <span class="badge bg-primary fs-6"><?= $judge_completion['percentage'] ?>%</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $judge_completion['percentage'] ?>%;" aria-valuenow="<?= $judge_completion['percentage'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <!-- Judges List with Checkmarks -->
                    <?php if (!empty($judges_list)): ?>
                        <div class="judges-list">
                            <?php foreach ($judges_list as $judge): ?>
                                <div class="judge-item d-flex align-items-center justify-content-between">
                                    <span class="fw-medium"><?= esc($judge['full_name']) ?></span>
                                    <div class="text-end">
                                        <?php if (!empty($judge['completed_at'])): ?>
                                            <span class="badge bg-success rounded-pill">Complete</span>
                                            <br>
                                            <small class="text-muted"><?= date('M d, h:i A', strtotime($judge['completed_at'])) ?></small>
                                        <?php else: ?>
                                            <span class="badge bg-secondary rounded-pill">Pending</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle" style="font-size: 3rem; color: #d1d5db;"></i>
                            <p class="text-muted mt-3 mb-0">No judges assigned to this round yet.</p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Round Completion Info -->
                    <?php if ($judge_completion['percentage'] >= 100 && $judge_completion['total'] > 0): ?>
                        <div class="mt-4">
                            <div class="alert alert-success border-0 shadow-sm">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <strong>All judges have completed scoring!</strong>
                                <p class="mb-0 mt-2 small">This round will automatically be marked as completed. Go to <a href="<?= base_url('admin/rounds-criteria') ?>" class="alert-link fw-bold">Rounds & Criteria Management</a> to activate the next round.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #d1d5db;"></i>
                        <p class="text-muted mt-3 mb-0">No active round.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
