<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Round Details<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .segment-card {
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .segment-header-1 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
    }
    
    .segment-header-2 {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 20px;
    }
    
    .criteria-table {
        margin: 0;
    }
    
    .criteria-table th {
        background: #f8f9fa;
        font-weight: 600;
    }
    
    .percentage-bar {
        height: 8px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .percentage-fill {
        height: 100%;
        background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
        transition: width 0.3s;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2">
            <span class="badge bg-primary me-2">Round <?= $round['round_number'] ?></span>
            <?= esc($round['round_name']) ?>
        </h1>
        <p class="text-muted">Round details, segments, and judging criteria</p>
    </div>
    <a href="<?= base_url('admin/rounds-criteria') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<!-- Round Info Card -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-info-circle-fill"></i> Round Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <strong>Round Number:</strong>
                <p><?= $round['round_number'] ?></p>
            </div>
            <div class="col-md-6">
                <strong>Round Name:</strong>
                <p><?= esc($round['round_name']) ?></p>
            </div>
            <div class="col-md-3">
                <strong>Status:</strong>
                <p>
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
                </p>
            </div>
        </div>
        
        <?php if (!empty($round['description'])): ?>
            <div class="row">
                <div class="col-12">
                    <strong>Description:</strong>
                    <p><?= nl2br(esc($round['description'])) ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-6">
                <strong>Number of Segments:</strong>
                <p>
                    <?php if ($round['segment_count'] == 1): ?>
                        <span class="badge bg-primary">1 Segment</span>
                    <?php else: ?>
                        <span class="badge bg-info">2 Segments</span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-md-6">
                <strong>Created:</strong>
                <p><?= date('F d, Y h:i A', strtotime($round['created_at'])) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Segments -->
<?php if (!empty($round['segments'])): ?>
    <?php foreach ($round['segments'] as $segment): ?>
        <div class="segment-card">
            <div class="segment-header-<?= $segment['segment_number'] ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-<?= $segment['segment_number'] ?>-circle-fill"></i>
                        Segment <?= $segment['segment_number'] ?>: <?= esc($segment['segment_name']) ?>
                    </h4>
                    <span class="badge bg-light text-dark fs-6">
                        Weight: <?= number_format($segment['weight_percentage'], 0) ?>%
                    </span>
                </div>
                <?php if (!empty($segment['description'])): ?>
                    <p class="mb-0 mt-2 opacity-75"><?= esc($segment['description']) ?></p>
                <?php endif; ?>
            </div>
            
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="bi bi-list-check"></i> Judging Criteria
                    <span class="badge bg-success ms-2">
                        <?= count($segment['criteria']) ?> criteria
                    </span>
                </h6>
                
                <?php if (!empty($segment['criteria'])): ?>
                    <div class="table-responsive">
                        <table class="table criteria-table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Criteria Name</th>
                                    <th width="35%">Description</th>
                                    <th width="10%" class="text-center">Max Score</th>
                                    <th width="15%">Percentage</th>
                                    <th width="10%" class="text-center">Weight</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($segment['criteria'] as $index => $criteria): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <strong><?= esc($criteria['criteria_name']) ?></strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= !empty($criteria['description']) ? esc($criteria['description']) : '<em>No description</em>' ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?= $criteria['max_score'] ?></span>
                                        </td>
                                        <td>
                                            <div class="percentage-bar mb-1">
                                                <div class="percentage-fill" style="width: <?= $criteria['percentage'] ?>%"></div>
                                            </div>
                                            <small><?= number_format($criteria['percentage'], 2) ?>%</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">
                                                <?= number_format(($criteria['percentage'] * $segment['weight_percentage']) / 100, 2) ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td colspan="2">
                                        <?php
                                        $totalPercentage = array_sum(array_column($segment['criteria'], 'percentage'));
                                        $badgeClass = abs($totalPercentage - 100) < 0.01 ? 'success' : 'danger';
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?> fs-6">
                                            <?= number_format($totalPercentage, 2) ?>%
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> No criteria defined for this segment.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> No segments found for this round.
    </div>
<?php endif; ?>

<!-- Summary Card -->
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-check2-circle"></i> Summary</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-4">
                <h3 class="text-primary"><?= count($round['segments']) ?></h3>
                <p class="text-muted">Segments</p>
            </div>
            <div class="col-md-4">
                <h3 class="text-success">
                    <?php
                    $totalCriteria = 0;
                    foreach ($round['segments'] as $seg) {
                        $totalCriteria += count($seg['criteria']);
                    }
                    echo $totalCriteria;
                    ?>
                </h3>
                <p class="text-muted">Total Criteria</p>
            </div>
            <div class="col-md-4">
                <h3 class="text-info">100%</h3>
                <p class="text-muted">Total Weight</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
