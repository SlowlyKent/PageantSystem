<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Rounds & Criteria<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .round-card {
        transition: all 0.3s;
        border-left: 4px solid #667eea;
    }
    
    .round-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    
    .segment-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .segment-1 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .segment-2 {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-diagram-3-fill"></i> Rounds & Criteria Management</h1>
        <p class="text-muted">Manage pageant rounds, segments, and judging criteria</p>
    </div>
    <a href="<?= base_url('admin/rounds-criteria/create') ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle"></i> Add New Round
    </a>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Rounds List -->
<?php if (empty($rounds)): ?>
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox" style="font-size: 64px; color: #ccc;"></i>
            <h4 class="mt-3 text-muted">No Rounds Created Yet</h4>
            <p class="text-muted">Start by creating your first pageant round with segments and criteria.</p>
            <a href="<?= base_url('admin/rounds-criteria/create') ?>" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-plus-circle"></i> Create First Round
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($rounds as $round): ?>
            <div class="col-md-6 mb-4">
                <div class="card round-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="mb-1">
                                    <span class="badge bg-primary me-2">Round <?= $round['round_number'] ?></span>
                                    <?= esc($round['round_name']) ?>
                                </h4>
                                <?php if (!empty($round['description'])): ?>
                                    <p class="text-muted small mb-2"><?= esc($round['description']) ?></p>
                                <?php endif; ?>
                            </div>
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
                        
                        <!-- Segments Info -->
                        <div class="mb-3">
                            <strong class="d-block mb-2">Segments:</strong>
                            <?php if ($round['segment_count'] == 1): ?>
                                <span class="segment-badge segment-1">
                                    <i class="bi bi-circle-fill"></i> 1 Segment
                                </span>
                            <?php else: ?>
                                <span class="segment-badge segment-2">
                                    <i class="bi bi-circle-fill"></i> <i class="bi bi-circle-fill"></i> 2 Segments
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admin/rounds-criteria/view/' . $round['id']) ?>" 
                               class="btn btn-sm btn-info text-white flex-fill">
                                <i class="bi bi-eye-fill"></i> View Details
                            </a>
                            <button 
                                onclick="confirmDelete(<?= $round['id'] ?>, '<?= esc($round['round_name']) ?>')"
                                class="btn btn-sm btn-danger">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> Created <?= date('M d, Y', strtotime($round['created_at'])) ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Summary Card -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <h3 class="text-primary"><?= count($rounds) ?></h3>
                    <p class="text-muted">Total Rounds</p>
                </div>
                <div class="col-md-4">
                    <h3 class="text-success">
                        <?= count(array_filter($rounds, fn($r) => $r['status'] === 'active')) ?>
                    </h3>
                    <p class="text-muted">Active</p>
                </div>
                <div class="col-md-4">
                    <h3 class="text-info">
                        <?= count(array_filter($rounds, fn($r) => $r['status'] === 'completed')) ?>
                    </h3>
                    <p class="text-muted">Completed</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(roundId, roundName) {
    if (confirm(`Are you sure you want to delete "${roundName}"?\n\nThis will also delete all segments and criteria for this round.\n\nThis action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('admin/rounds-criteria/delete/') ?>' + roundId;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>
