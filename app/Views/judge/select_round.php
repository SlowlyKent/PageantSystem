<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Select Round<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .round-selection-card {
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid #e0e0e0;
        height: 100%;
    }
    
    .round-selection-card:hover {
        transform: translateY(-10px);
        border-color: #667eea;
        box-shadow: 0 8px 20px rgba(102,126,234,0.3);
    }
    
    .round-icon {
        font-size: 64px;
        margin-bottom: 15px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-ui-checks-grid"></i> Select Round to Score</h1>
    <p class="text-muted">Choose a round to start scoring contestants</p>
</div>

<?php if (empty($rounds)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No active rounds available for scoring.
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($rounds as $round): ?>
            <div class="col-md-4 mb-4">
                <a href="<?= base_url("judge/score-round/{$round['id']}") ?>" class="text-decoration-none">
                    <div class="card round-selection-card">
                        <div class="card-body text-center py-5">
                            <div class="round-icon">🏆</div>
                            <h3>Round <?= $round['round_number'] ?></h3>
                            <h5 class="text-primary"><?= esc($round['round_name']) ?></h5>
                            <p class="text-muted small"><?= esc($round['description']) ?></p>
                            <span class="badge bg-info"><?= $round['segment_count'] ?> Segment(s)</span>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
