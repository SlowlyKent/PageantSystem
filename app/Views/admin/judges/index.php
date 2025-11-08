<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Judges Management<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .action-btn {
        padding: 5px 10px;
        margin: 0 2px;
        border-radius: 5px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .btn-view {
        background: #17a2b8;
        color: white;
    }
    
    .btn-view:hover {
        background: #138496;
        color: white;
    }
    
    .btn-edit {
        background: #ffc107;
        color: #333;
    }
    
    .btn-edit:hover {
        background: #e0a800;
        color: #333;
    }
    
    .btn-delete {
        background: #dc3545;
        color: white;
    }
    
    .btn-delete:hover {
        background: #c82333;
        color: white;
    }
    
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .status-active {
        background: #28a745;
        color: white;
    }
    
    .status-inactive {
        background: #6c757d;
        color: white;
    }
    
    .judges-table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-person-badge-fill"></i> Judges Management</h1>
        <p class="text-muted">Manage judge accounts and information</p>
    </div>
    <a href="<?= base_url('admin/judges/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Judge
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

<!-- Judges Table -->
<div class="judges-table">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($judges)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-3">No judges found. Click "Add New Judge" to create one.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($judges as $index => $judge): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <strong><?= esc($judge['username']) ?></strong>
                            </td>
                            <td><?= esc($judge['full_name']) ?></td>
                            <td>
                                <i class="bi bi-envelope"></i> <?= esc($judge['email']) ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?= $judge['status'] ?>">
                                    <?= ucfirst($judge['status']) ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M d, Y', strtotime($judge['created_at'])) ?>
                                </small>
                            </td>
                            <td class="text-center">
                                <!-- View Button -->
                                <a href="<?= base_url('admin/judges/view/' . $judge['id']) ?>" 
                                   class="btn btn-sm action-btn btn-view"
                                   title="View Details">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                
                                <!-- Edit Button -->
                                <a href="<?= base_url('admin/judges/edit/' . $judge['id']) ?>" 
                                   class="btn btn-sm action-btn btn-edit"
                                   title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                
                                <!-- Delete Button -->
                                <button 
                                    onclick="confirmDelete(<?= $judge['id'] ?>, '<?= esc($judge['username']) ?>')"
                                    class="btn btn-sm action-btn btn-delete"
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

<!-- Summary Card -->
<?php if (!empty($judges)): ?>
    <div class="mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h3 class="text-primary"><?= count($judges) ?></h3>
                        <p class="text-muted">Total Judges</p>
                    </div>
                    <div class="col-md-4">
                        <h3 class="text-success">
                            <?= count(array_filter($judges, fn($j) => $j['status'] === 'active')) ?>
                        </h3>
                        <p class="text-muted">Active</p>
                    </div>
                    <div class="col-md-4">
                        <h3 class="text-secondary">
                            <?= count(array_filter($judges, fn($j) => $j['status'] === 'inactive')) ?>
                        </h3>
                        <p class="text-muted">Inactive</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/**
 * Confirm delete with user
 * Shows confirmation dialog before deleting judge
 */
function confirmDelete(judgeId, username) {
    if (confirm(`Are you sure you want to delete judge "${username}"?\n\nThis action cannot be undone.`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('admin/judges/delete/') ?>' + judgeId;
        
        // Add CSRF token
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
