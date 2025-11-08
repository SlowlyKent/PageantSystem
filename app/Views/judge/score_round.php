<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Score Contestants<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2">
            <span class="badge bg-primary me-2">Round <?= $round['round_number'] ?></span>
            <?= esc($round['round_name']) ?>
        </h1>
        <p class="text-muted">Click on a contestant to enter scores</p>
    </div>
    <a href="<?= base_url('judge/select-round') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Change Round
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <?php foreach ($contestants as $contestant): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <?php if (!empty($contestant['profile_picture'])): ?>
                                <img src="<?= base_url('uploads/contestants/' . $contestant['profile_picture']) ?>" 
                                     class="rounded-circle" 
                                     width="60" 
                                     height="60"
                                     style="object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px;">
                                    <i class="bi bi-person fs-3 text-white"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0"><?= esc($contestant['first_name'] . ' ' . $contestant['last_name']) ?></h5>
                            <small class="text-muted">Contestant #<?= esc($contestant['contestant_number']) ?></small>
                        </div>
                    </div>
                    
                    <?php if ($contestant['scored']): ?>
                        <span class="badge bg-success w-100 mb-2">
                            <i class="bi bi-check-circle-fill"></i> Scored
                        </span>
                        <a href="<?= base_url("judge/score-contestant/{$round['id']}/{$contestant['id']}") ?>" 
                           class="btn btn-warning w-100">
                            <i class="bi bi-pencil"></i> Edit Scores
                        </a>
                    <?php else: ?>
                        <span class="badge bg-secondary w-100 mb-2">
                            <i class="bi bi-dash-circle"></i> Not Scored
                        </span>
                        <a href="<?= base_url("judge/score-contestant/{$round['id']}/{$contestant['id']}") ?>" 
                           class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square"></i> Enter Scores
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
