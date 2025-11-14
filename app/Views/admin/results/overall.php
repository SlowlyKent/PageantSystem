<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Overall Rankings<?= $this->endSection() ?>

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
    <div class="alert alert-info mb-4">
        <h5 class="alert-heading mb-0"><i class="bi bi-info-circle"></i> No completed rounds with scores yet.</h5>
    </div>
<?php else: ?>
    <!-- Winner Spotlight -->
    <?php if (isset($rankings[0])): ?>
        <div class="winner-card mb-5">
            <div class="winner-trophy">👑</div>
            <h2 class="mt-3">Winner</h2>
            <h1 class="display-4"><?= esc($rankings[0]['contestant_name']) ?></h1>
            <p class="fs-5">Contestant #<?= $rankings[0]['contestant_number'] ?></p>
            <h2 class="mt-3">Average Score: <?= number_format($rankings[0]['total_score'], 2) ?></h2>
            <?php if (!empty($rankings[0]['rounds_completed'])): ?>
            <?php endif; ?>
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
                            <th width="35%">Name</th>
                            <th width="20%" class="text-center">Average Score</th>
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
                                             class="rounded-circle leaderboard-avatar">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center leaderboard-avatar-placeholder">
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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
