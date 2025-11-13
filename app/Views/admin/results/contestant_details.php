<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Contestant Detailed Scores<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .judge-card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .criteria-row:hover {
        background: #f8f9fa;
    }
    .profile-photo {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #e9ecef;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2">
            <i class="bi bi-clipboard-data"></i>
            <?= esc($round['round_name']) ?> — Contestant Details
        </h1>
        <p class="text-muted mb-0">
            Round <?= $round['round_number'] ?> • Contestant #<?= esc($contestant['contestant_number']) ?> —
            <strong><?= esc($contestant['first_name'] . ' ' . $contestant['last_name']) ?></strong>
        </p>
    </div>
    <a href="<?= base_url('admin/results/round/' . $round['id']) ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<?php if (empty($scores_by_judge)): ?>
    <div class="alert alert-info">
        <h5 class="alert-heading"><i class="bi bi-info-circle"></i> No scores found.</h5>
        <p class="mb-0">This contestant has not been scored yet for this round.</p>
    </div>
<?php else: ?>

    <div class="row g-4">
        <?php foreach ($scores_by_judge as $judgeId => $data): ?>
            <div class="col-12">
                <div class="card judge-card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-person-badge"></i>
                            <?= esc($data['judge_name']) ?>
                        </h5>
                        <span class="badge bg-light text-dark"><?= count($data['scores']) ?> criteria scored</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50%">Criteria</th>
                                        <th width="20%" class="text-center">Weight</th>
                                        <th width="30%" class="text-center">Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $total = 0;
                                        foreach ($data['scores'] as $row): 
                                            $total += (float)$row['score'];
                                    ?>
                                        <tr class="criteria-row">
                                            <td><?= esc($row['criteria_name']) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary"><?= rtrim(rtrim(number_format((float)($row['percentage'] ?? 0), 2), '0'), '.') ?>%</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">
                                                    <?= number_format($row['score'], 2) ?>
                                                    <?php if (!empty($row['max_score'])): ?>
                                                        <small class="text-white-50">/ <?= number_format($row['max_score'], 0) ?></small>
                                                    <?php endif; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-end">Total</th>
                                        <th class="text-center">
                                            <span class="badge bg-primary"><?= number_format($total, 2) ?></span>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>


