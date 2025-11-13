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
    
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-diagram-3-fill"></i> Rounds & Criteria Management</h1>
        <p class="text-muted">Manage pageant rounds and their judging criteria</p>
    </div>
    <a href="<?= base_url('admin/rounds-criteria/create') ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle"></i> Add Round
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

<!-- Summary Card -->
<?php if (!empty($rounds)): ?>
    <div class="card summary-stats mt-3 mb-3">
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

<!-- Rounds List -->
<?php if (empty($rounds)): ?>
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox" style="font-size: 64px; color: #ccc;"></i>
            <h4 class="mt-3 text-muted">No Rounds Created Yet</h4>
            <p class="text-muted">Start by creating your first pageant round and defining its judging criteria.</p>
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
                            </div>
                            <div>
                                <?php
                                $statusClass = match($round['status']) {
                                    'active' => 'success',
                                    'pending' => 'warning',
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
                        
                        <!-- Criteria Info -->
                        <div class="mb-3">
                            <strong class="d-block mb-2">Criteria:</strong>
                            <span class="badge bg-primary">
                                <?= $round['criteria_count'] ?? 0 ?> item<?= ($round['criteria_count'] ?? 0) === 1 ? '' : 's' ?>
                            </span>
                        </div>
                        
                        <!-- Judge Completion Status -->
                        <?php
                        $db = \Config\Database::connect();
                        // Count ALL active judges in the system (not just those who accessed this round)
                        $totalJudges = $db->table('users')
                            ->join('roles', 'roles.id = users.role_id')
                            ->where('roles.name', 'judge')
                            ->where('users.status', 'active')
                            ->countAllResults();
                        // Count how many active judges have completed this round
                        $completedJudges = $db->table('users')
                            ->join('roles', 'roles.id = users.role_id')
                            ->join('round_judges', 'round_judges.judge_id = users.id AND round_judges.round_id = ' . (int)$round['id'])
                            ->where('roles.name', 'judge')
                            ->where('users.status', 'active')
                            ->where('round_judges.completed_at IS NOT NULL', null, false)
                            ->countAllResults();
                        ?>
                        <?php if ($totalJudges > 0): ?>
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="bi bi-people-fill"></i> Judges: <?= $completedJudges ?>/<?= $totalJudges ?> completed
                                <?php if ($completedJudges == $totalJudges && $totalJudges > 0): ?>
                                    <span class="badge bg-success ms-2"><i class="bi bi-check-all"></i> All Done</span>
                                <?php endif; ?>
                            </small>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('admin/rounds-criteria/view/' . $round['id']) ?>" 
                                   class="btn btn-sm btn-info text-white flex-fill">
                                    <i class="bi bi-eye-fill"></i> View
                                </a>
                                <a href="<?= base_url('admin/rounds-criteria/edit/' . $round['id']) ?>" 
                                   class="btn btn-sm btn-secondary flex-fill">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <button 
                                    onclick="confirmDelete(<?= $round['id'] ?>, '<?= esc($round['round_name']) ?>')"
                                    class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>

                            <!-- Status Management Buttons -->
                            <?php if ($round['status'] === 'pending' || $round['status'] === 'inactive'): ?>
                                <form action="<?= base_url('admin/rounds-criteria/activate/' . $round['id']) ?>" method="post" class="d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-primary flex-fill" onclick="return confirm('Activate this round? Judges will be able to access it.')">
                                        <i class="bi bi-play-circle-fill"></i> Activate Round
                                    </button>
                                </form>
                            <?php elseif ($round['status'] === 'completed' && $completedJudges > 0): ?>
                                <form action="<?= base_url('admin/rounds-criteria/reset-judges/' . $round['id']) ?>" method="post" class="d-flex gap-2">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-warning flex-fill" onclick="return confirm('Reset all judge completions for this round? Judges will need to re-score.')">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset Judge Scores
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <?php if (!empty($round['is_elimination'])): ?>
                                <form class="d-flex align-items-center gap-2" action="<?= base_url('admin/rounds-criteria/eliminate/' . $round['id']) ?>" method="post" onsubmit="return confirm('Proceed with elimination and advance Top N to next round?');">
                                    <input type="number" class="form-control form-control-sm" name="elimination_quota" min="1" placeholder="Top N" style="max-width:120px;" value="<?= esc($round['elimination_quota'] ?? '') ?>">
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        <i class="bi bi-filter-circle"></i> Eliminate
                                    </button>
                                </form>
                            <?php endif; ?>
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
    
    <!-- Summary Card moved to top -->
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(roundId, roundName) {
    if (confirm(`Are you sure you want to delete "${roundName}"?\n\nThis will remove all criteria, judge scores, and assignments for this round.\n\nThis action cannot be undone.`)) {
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
