<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Round Rankings<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .podium-card {
        text-align: center;
        padding: 30px;
        border-radius: 15px;
        position: relative;
    }
    
    .rank-1 { 
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        transform: scale(1.1);
        z-index: 3;
    }
    
    .rank-2 { 
        background: linear-gradient(135deg, #C0C0C0 0%, #808080 100%);
        z-index: 2;
    }
    
    .rank-3 { 
        background: linear-gradient(135deg, #CD7F32 0%, #8B4513 100%);
        z-index: 1;
    }
    
    .podium-number {
        font-size: 64px;
        font-weight: bold;
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .ranking-table tbody tr:hover {
        background: #f8f9fa;
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2">
            <span class="badge bg-primary me-2">Round <?= $round['round_number'] ?></span>
            <?= esc($round['round_name']) ?>
        </h1>
        <p class="text-muted">Rankings based on judges' scores (averaged)</p>
    </div>
    <a href="<?= base_url('admin/results') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<?php if (empty($rankings)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No scores submitted for this round yet.
    </div>
<?php else: ?>
    <!-- Top 3 Podium -->
    <?php if (count($rankings) >= 3): ?>
        <div class="row mb-5">
            <!-- 2nd Place -->
            <div class="col-md-4 d-flex align-items-end">
                <div class="card podium-card rank-2 w-100">
                    <div class="podium-number">2</div>
                    <h5 class="text-white"><?= esc($rankings[1]['contestant_name']) ?></h5>
                    <p class="text-white mb-0">Contestant #<?= $rankings[1]['contestant_number'] ?></p>
                    <h3 class="text-white mt-2"><?= number_format($rankings[1]['total_score'], 2) ?></h3>
                </div>
            </div>
            
            <!-- 1st Place -->
            <div class="col-md-4">
                <div class="card podium-card rank-1 w-100">
                    <i class="bi bi-trophy-fill" style="font-size: 48px; color: white;"></i>
                    <div class="podium-number">1</div>
                    <h4 class="text-white"><?= esc($rankings[0]['contestant_name']) ?></h4>
                    <p class="text-white mb-0">Contestant #<?= $rankings[0]['contestant_number'] ?></p>
                    <h2 class="text-white mt-2"><?= number_format($rankings[0]['total_score'], 2) ?></h2>
                </div>
            </div>
            
            <!-- 3rd Place -->
            <div class="col-md-4 d-flex align-items-end">
                <div class="card podium-card rank-3 w-100">
                    <div class="podium-number">3</div>
                    <h5 class="text-white"><?= esc($rankings[2]['contestant_name']) ?></h5>
                    <p class="text-white mb-0">Contestant #<?= $rankings[2]['contestant_number'] ?></p>
                    <h3 class="text-white mt-2"><?= number_format($rankings[2]['total_score'], 2) ?></h3>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Full Rankings Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-list-ol"></i> Complete Rankings</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table ranking-table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="10%" class="text-center">Rank</th>
                            <th width="15%">Contestant #</th>
                            <th width="35%">Name</th>
                            <th width="20%" class="text-center">Average Score</th>
                            <th width="10%" class="text-center">Judges</th>
                            <th width="10%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rankings as $ranking): ?>
                            <tr>
                                <td class="text-center">
                                    <?php if ($ranking['rank'] == 1): ?>
                                        <span class="badge bg-warning text-dark fs-5">🥇 <?= $ranking['rank'] ?></span>
                                    <?php elseif ($ranking['rank'] == 2): ?>
                                        <span class="badge bg-secondary fs-5">🥈 <?= $ranking['rank'] ?></span>
                                    <?php elseif ($ranking['rank'] == 3): ?>
                                        <span class="badge bg-info fs-5">🥉 <?= $ranking['rank'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark fs-5"><?= $ranking['rank'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= esc($ranking['contestant_number']) ?></strong></td>
                                <td><?= esc($ranking['contestant_name']) ?></td>
                                <td class="text-center">
                                    <span class="badge bg-primary fs-6">
                                        <?= number_format($ranking['total_score'], 2) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info"><?= $ranking['judge_count'] ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url("admin/results/contestant/{$round['id']}/{$ranking['contestant_id']}") ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
