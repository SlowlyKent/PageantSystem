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
        <p class="text-muted">Combined rankings across <?= $total_completed_rounds ?> completed round(s)</p>
    </div>
    <div>
        <a href="<?= base_url('judge/select-round') ?>" class="btn btn-secondary text-white">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<?php if (empty($overall_rankings)): ?>
    <div class="alert alert-info mb-4">
        <h5 class="alert-heading mb-0"><i class="bi bi-info-circle"></i> No completed rounds with scores yet.</h5>
    </div>
<?php else: ?>
    <!-- Winner Spotlight -->
    <?php if (isset($overall_rankings[0])): ?>
        <div class="winner-card mb-5">
            <div class="winner-trophy">👑</div>
            <h2 class="mt-3">Winner</h2>
            <h1 class="display-4"><?= esc($overall_rankings[0]['contestant_name']) ?></h1>
            <p class="fs-5">Contestant #<?= $overall_rankings[0]['contestant_number'] ?></p>
            <h2 class="mt-3">Average Score: <?= number_format($overall_rankings[0]['total_score'], 2) ?></h2>
            <p class="mb-0">Completed <?= $overall_rankings[0]['rounds_completed'] ?> round(s)</p>
        </div>
    <?php endif; ?>
    
    <!-- Full Rankings Table -->
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
                            <th width="15%">Contestant #</th>
                            <th width="40%">Name</th>
                            <th width="25%" class="text-center">Average Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($overall_rankings as $ranking): ?>
                            <tr class="contestant-row">
                                <td class="text-center">
                                    <h5 class="mb-0"><?= $ranking['rank'] ?></h5>
                                </td>
                                <td><strong><?= esc($ranking['contestant_number']) ?></strong></td>
                                <td><strong><?= esc($ranking['contestant_name']) ?></strong></td>
                                <td class="text-center">
                                    <span class="badge bg-success fs-6">
                                        <?= number_format($ranking['total_score'], 2) ?>
                                    </span>
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