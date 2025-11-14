<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Round Rankings<?= $this->endSection() ?>

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
        <h5 class="alert-heading"><i class="bi bi-info-circle"></i> No scores submitted for this round yet.</h5>
        <hr>
        <p class="mb-2"><strong>To see rankings here, the following needs to happen:</strong></p>
        <ol class="mb-2">
            <li>Round must have status = "<strong>active</strong>" (activate it in <a href="<?= base_url('admin/rounds-criteria') ?>" class="alert-link">Rounds & Criteria Management</a>)</li>
            <li>Judges must be assigned to this round</li>
            <li>Judges need to score contestants through their Judge Panel</li>
        </ol>
        
        <?php if (isset($total_judges) && isset($completed_judges)): ?>
        <div class="mt-3">
            <p class="mb-1"><strong>Current Status:</strong></p>
            <ul class="mb-0">
                <li>Round Status: <span class="badge bg-<?= $round['status'] == 'active' ? 'success' : ($round['status'] == 'completed' ? 'primary' : 'warning') ?>"><?= ucfirst($round['status']) ?></span></li>
                <li>Judges Assigned: <strong><?= $total_judges ?></strong></li>
                <li>Judges Completed: <strong><?= $completed_judges ?> / <?= $total_judges ?></strong></li>
            </ul>
        </div>
        <?php endif; ?>
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
                    <i class="bi bi-trophy-fill podium-trophy-icon"></i>
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
                            <th width="12%" class="text-center">Rank</th>
                            <th width="18%">Contestant #</th>
                            <th width="40%">Name</th>
                            <th width="20%" class="text-center">Average Score</th>
                            <th width="10%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rankings as $ranking): ?>
                            <tr>
                                <td class="text-center">
                                    <?php if ($ranking['rank'] == 1): ?>
                                        <span class="badge bg-warning text-dark fs-5"><i class="bi bi-trophy-fill"></i> <?= $ranking['rank'] ?></span>
                                    <?php elseif ($ranking['rank'] == 2): ?>
                                        <span class="badge bg-secondary fs-5"><i class="bi bi-trophy"></i> <?= $ranking['rank'] ?></span>
                                    <?php elseif ($ranking['rank'] == 3): ?>
                                        <span class="badge bg-info fs-5"><i class="bi bi-award-fill"></i> <?= $ranking['rank'] ?></span>
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
