<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Overall Rankings<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .winner-card {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        color: white;
        box-shadow: 0 10px 30px rgba(255,215,0,0.3);
    }
    
    .winner-trophy {
        font-size: 120px;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-20px); }
        60% { transform: translateY(-10px); }
    }
    
    .contestant-row {
        transition: all 0.3s;
    }
    
    .contestant-row:hover {
        background: #f8f9fa;
        transform: scale(1.02);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-trophy-fill"></i> Overall Rankings</h1>
        <p class="text-muted">Combined rankings across <?= $total_rounds ?> completed round(s)</p>
    </div>
    <a href="<?= base_url('admin/results') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<?php if (empty($rankings)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No completed rounds with scores yet.
    </div>
<?php else: ?>
    <!-- Winner Spotlight -->
    <?php if (isset($rankings[0])): ?>
        <div class="winner-card mb-5">
            <div class="winner-trophy">🏆</div>
            <h2 class="mt-3">Overall Winner</h2>
            <h1 class="display-4"><?= esc($rankings[0]['contestant_name']) ?></h1>
            <p class="fs-5">Contestant #<?= $rankings[0]['contestant_number'] ?></p>
            <h2 class="mt-3">Average Score: <?= number_format($rankings[0]['total_score'], 2) ?></h2>
            <p class="mb-0">Completed <?= $rankings[0]['rounds_completed'] ?> round(s)</p>
        </div>
    <?php endif; ?>
    
    <!-- Top 10 or All Rankings -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-list-ol"></i> Full Rankings</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="10%" class="text-center">Rank</th>
                            <th width="10%">Photo</th>
                            <th width="15%">Contestant #</th>
                            <th width="30%">Name</th>
                            <th width="15%" class="text-center">Average Score</th>
                            <th width="10%" class="text-center">Rounds</th>
                            <th width="10%" class="text-center">Medal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rankings as $ranking): ?>
                            <tr class="contestant-row">
                                <td class="text-center">
                                    <?php if ($ranking['rank'] <= 3): ?>
                                        <h3 class="mb-0">
                                            <?php if ($ranking['rank'] == 1): ?>
                                                <span class="text-warning">1</span>
                                            <?php elseif ($ranking['rank'] == 2): ?>
                                                <span class="text-secondary">2</span>
                                            <?php else: ?>
                                                <span class="text-info">3</span>
                                            <?php endif; ?>
                                        </h3>
                                    <?php else: ?>
                                        <span class="text-muted fs-5"><?= $ranking['rank'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($ranking['profile_picture'])): ?>
                                        <img src="<?= base_url('uploads/contestants/' . $ranking['profile_picture']) ?>" 
                                             class="rounded-circle" 
                                             width="50" 
                                             height="50"
                                             style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-person text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= esc($ranking['contestant_number']) ?></strong></td>
                                <td>
                                    <strong><?= esc($ranking['contestant_name']) ?></strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success fs-6">
                                        <?= number_format($ranking['total_score'], 2) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info"><?= $ranking['rounds_completed'] ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($ranking['rank'] == 1): ?>
                                        <span style="font-size: 32px;">🥇</span>
                                    <?php elseif ($ranking['rank'] == 2): ?>
                                        <span style="font-size: 32px;">🥈</span>
                                    <?php elseif ($ranking['rank'] == 3): ?>
                                        <span style="font-size: 32px;">🥉</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary"><?= count($rankings) ?></h3>
                    <p class="text-muted mb-0">Total Contestants</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success"><?= $total_rounds ?></h3>
                    <p class="text-muted mb-0">Completed Rounds</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning">
                        <?= isset($rankings[0]) ? number_format($rankings[0]['total_score'], 2) : '0.00' ?>
                    </h3>
                    <p class="text-muted mb-0">Highest Average Score</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
