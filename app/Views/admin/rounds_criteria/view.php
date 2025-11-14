<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Round Details<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">
            <span class="badge bg-primary me-2">Round <?= $round['round_number'] ?></span>
            <?= esc($round['round_name']) ?>
        </h1>
        <p class="text-muted mb-0 small">Round details and judging criteria</p>
    </div>
    <a href="<?= base_url('admin/rounds-criteria') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<!-- Round Info Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h6 class="text-uppercase text-muted mb-4 section-label">Round Information</h6>
        
        <div class="row g-4">
            <div class="col-md-2">
                <div class="text-muted small mb-2">Round Number</div>
                <div class="fw-bold fs-5"><?= $round['round_number'] ?></div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small mb-2">Round Name</div>
                <div class="fw-bold"><?= esc($round['round_name']) ?></div>
            </div>
            <div class="col-md-2">
                <div class="text-muted small mb-2">Status</div>
                <div>
                    <?php
                    $statusClass = match($round['status']) {
                        'active' => 'success',
                        'inactive' => 'secondary',
                        'completed' => 'info',
                        default => 'secondary'
                    };
                    ?>
                    <span class="badge bg-<?= $statusClass ?>">
                        <?= ucfirst($round['status']) ?>
                    </span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="text-muted small mb-2">Criteria</div>
                <div>
                    <span class="badge bg-primary"><?= isset($round['criteria']) ? count($round['criteria']) : 0 ?> Item<?= (isset($round['criteria']) ? count($round['criteria']) : 0) === 1 ? '' : 's' ?></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="text-muted small mb-2">Max Score</div>
                <div class="fw-bold fs-5"><?= isset($round['max_score']) ? number_format($round['max_score'], 2) : '100.00' ?></div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small mb-2">Created</div>
                <div class="small"><?= date('M d, Y h:i A', strtotime($round['created_at'])) ?></div>
            </div>
        </div>
        
        <?php if (!empty($round['description'])): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="text-muted small mb-2">Description</div>
                    <div class="text-secondary"><?= nl2br(esc($round['description'])) ?></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Criteria -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Judging Criteria</h5>
            <span class="badge bg-secondary"><?= isset($round['criteria']) ? count($round['criteria']) : 0 ?> item<?= (isset($round['criteria']) ? count($round['criteria']) : 0) === 1 ? '' : 's' ?></span>
        </div>

        <?php if (!empty($round['criteria'])): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered-light">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="30%">Criteria Name</th>
                            <th width="40%">Description</th>
                            <th width="10%" class="text-center">% Weight</th>
                            <th width="10%" class="text-center">Max Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($round['criteria'] as $index => $criteria): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><strong><?= esc($criteria['criteria_name']) ?></strong></td>
                                <td class="text-muted small">
                                    <?= !empty($criteria['description']) ? esc($criteria['description']) : '<em>No description</em>' ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary"><?= number_format($criteria['percentage'], 2) ?>%</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success"><?= number_format($criteria['max_score'], 2) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td class="text-center">
                                <?php
                                $totalPercentage = array_sum(array_column($round['criteria'], 'percentage'));
                                $badgeClass = abs($totalPercentage - 100) < 0.01 ? 'success' : 'danger';
                                ?>
                                <span class="badge bg-<?= $badgeClass ?> fs-6">
                                    <?= number_format($totalPercentage, 2) ?>%
                                </span>
                            </td>
                            <td class="text-center">
                                <?php $totalMaxScore = array_sum(array_column($round['criteria'], 'max_score')); ?>
                                <span class="badge bg-success fs-6">
                                    <?= number_format($totalMaxScore, 2) ?>
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle"></i> No criteria defined for this round.
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
