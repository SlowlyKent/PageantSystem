<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Score Contestants<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .round-card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s;
        border: 2px solid #e0e0e0;
        height: 100%;
    }
    
    .round-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        border-color: #667eea;
    }
    
    .round-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        text-align: center;
    }
    
    .round-icon {
        font-size: 48px;
        margin-bottom: 10px;
    }
    
    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 15px;
        border-radius: 20px;
        background: #f8f9fa;
        margin: 5px;
    }
    
    .quick-stats {
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
    }
    
    .stat-card {
        text-align: center;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #667eea;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-ui-checks"></i> Score Contestants</h1>
        <p class="text-muted">Select a round to begin scoring contestants</p>
    </div>
    <div>
        <a href="<?= base_url('judge/dashboard') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-house"></i> Dashboard
        </a>
    </div>
</div>

<!-- Quick Stats -->
<div class="quick-stats">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="stat-card">
                <i class="bi bi-trophy-fill text-warning" style="font-size: 2rem;"></i>
                <div class="stat-number"><?= count($rounds ?? []) ?></div>
                <div class="text-muted">Active Rounds</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                <div class="stat-number"><?= $total_contestants ?? 0 ?></div>
                <div class="text-muted">Total Contestants</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                <div class="stat-number"><?= $scores_submitted ?? 0 ?></div>
                <div class="text-muted">Scores Submitted</div>
            </div>
        </div>
    </div>
</div>

<!-- Instructions -->
<div class="alert alert-info border-0 shadow-sm">
    <div class="d-flex align-items-center">
        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
        <div>
            <strong>How to Score:</strong>
            <ol class="mb-0 mt-2">
                <li>Select a round below</li>
                <li>Choose a contestant to score</li>
                <li>Enter scores for each criterion (criteria are set by admin)</li>
                <li>Submit your scores</li>
            </ol>
        </div>
    </div>
</div>

<!-- Rounds Selection -->
<h4 class="mb-3"><i class="bi bi-trophy"></i> Available Rounds</h4>

<?php if (empty($rounds)): ?>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        No active rounds available. Please contact the administrator.
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($rounds as $round): ?>
            <div class="col-md-6 col-lg-4">
                <div class="round-card">
                    <div class="round-header">
                        <div class="round-icon">🏆</div>
                        <h3 class="mb-0">Round <?= $round['round_number'] ?></h3>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-primary mb-3"><?= esc($round['round_name']) ?></h5>
                        
                        <?php if (!empty($round['description'])): ?>
                            <p class="text-muted small mb-3"><?= esc($round['description']) ?></p>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <div class="info-badge">
                                <i class="bi bi-layers-fill text-primary"></i>
                                <span><?= $round['segment_count'] ?> Segment(s)</span>
                            </div>
                            <div class="info-badge">
                                <i class="bi bi-clipboard-check-fill text-success"></i>
                                <span><?= $round['status'] === 'active' ? 'Active' : 'Inactive' ?></span>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="<?= base_url("judge/score-round/{$round['id']}") ?>" 
                               class="btn btn-primary btn-lg">
                                <i class="bi bi-pencil-square"></i> Start Scoring
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Help Section -->
<div class="card mt-4 border-0 shadow-sm">
    <div class="card-body">
        <h5><i class="bi bi-question-circle"></i> Need Help?</h5>
        <p class="text-muted mb-0">
            The scoring criteria are managed by the administrator. When the admin updates criteria 
            (adds, edits, or removes them), the changes will automatically appear in your scoring forms. 
            You can edit your scores at any time before the round closes.
        </p>
    </div>
</div>

<?= $this->endSection() ?>
