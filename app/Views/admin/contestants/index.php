<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Contestants Management<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-people-fill"></i> Contestants Management</h1>
        <p class="text-muted">Manage contestant information and profiles</p>
    </div>
    <a href="<?= base_url('admin/contestants/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Contestant
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
<?php if (!empty($contestants)): ?>
    <div class="mt-3 mb-3">
        <div class="card summary-stats">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h3 class="text-primary"><?= count($contestants) ?></h3>
                        <p class="text-muted">Total Contestants</p>
                    </div>
                    <div class="col-md-3">
                        <h3 class="text-success">
                            <?= count(array_filter($contestants, fn($c) => $c['status'] === 'active')) ?>
                        </h3>
                        <p class="text-muted">Active</p>
                    </div>
                    <div class="col-md-3">
                        <h3 class="text-secondary">
                            <?= count(array_filter($contestants, fn($c) => $c['status'] === 'inactive')) ?>
                        </h3>
                        <p class="text-muted">Inactive</p>
                    </div>
                    <div class="col-md-3">
                        <h3 class="text-danger">
                            <?= count(array_filter($contestants, fn($c) => $c['status'] === 'disqualified')) ?>
                        </h3>
                        <p class="text-muted">Disqualified</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Contestants Table -->
<div class="contestants-table">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Photo</th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>City</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contestants)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-inbox empty-state-icon icon-display-lg"></i>
                            <p class="text-muted mt-3">No contestants found. Click "Add New Contestant" to create one.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contestants as $contestant): ?>
                        <tr>
                            <td>
                                <?php if (!empty($contestant['profile_picture'])): ?>
                                    <img src="<?= base_url('uploads/contestants/' . $contestant['profile_picture']) ?>" 
                                         alt="<?= esc($contestant['first_name']) ?>" 
                                         class="contestant-photo">
                                <?php else: ?>
                                    <div class="no-photo">
                                        <i class="bi bi-person"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= esc($contestant['contestant_number']) ?></strong></td>
                            <td>
                                <?= esc($contestant['first_name']) ?> 
                                <?= !empty($contestant['middle_name']) ? esc($contestant['middle_name'][0]) . '.' : '' ?> 
                                <?= esc($contestant['last_name']) ?>
                            </td>
                            <td><?= esc($contestant['age']) ?></td>
                            <td><?= esc($contestant['city']) ?></td>
                            <td>
                                <small><i class="bi bi-phone"></i> <?= esc($contestant['contact_number']) ?></small>
                            </td>
                            <td>
                                <?php
                                $statusClass = match($contestant['status']) {
                                    'active' => 'bg-success',
                                    'inactive' => 'bg-secondary',
                                    'disqualified' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="status-badge <?= $statusClass ?> text-white">
                                    <?= ucfirst($contestant['status']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/contestants/view/' . $contestant['id']) ?>" 
                                   class="btn btn-sm action-btn btn-info text-white"
                                   title="View Details">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                
                                <a href="<?= base_url('admin/contestants/edit/' . $contestant['id']) ?>" 
                                   class="btn btn-sm action-btn btn-warning"
                                   title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                
                                <button 
                                    onclick="confirmDelete(<?= $contestant['id'] ?>, '<?= esc($contestant['first_name']) ?> <?= esc($contestant['last_name']) ?>')"
                                    class="btn btn-sm action-btn btn-danger"
                                    title="Delete">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Summary Card moved to top -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(contestantId, name) {
    if (confirm(`Are you sure you want to delete contestant "${name}"?\n\nThis will also delete their profile picture.\n\nThis action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('admin/contestants/delete/') ?>' + contestantId;
        
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
