<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Judge Details<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-eye-fill"></i> Judge Details</h1>
        <p class="text-muted">View judge account information</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/judges/edit/' . $judge['id']) ?>" class="btn btn-warning">
            <i class="bi bi-pencil-fill"></i> Edit
        </a>
        <a href="<?= base_url('admin/judges') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<!-- Judge Information Card -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> Account Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong><i class="bi bi-hash"></i> ID:</strong></div>
                    <div class="col-md-8"><?= esc($judge['id']) ?></div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4"><strong><i class="bi bi-person-circle"></i> Username:</strong></div>
                    <div class="col-md-8">
                        <span class="badge bg-secondary"><?= esc($judge['username']) ?></span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4"><strong><i class="bi bi-person-fill"></i> Full Name:</strong></div>
                    <div class="col-md-8"><?= esc($judge['full_name']) ?></div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4"><strong><i class="bi bi-envelope-fill"></i> Email:</strong></div>
                    <div class="col-md-8">
                        <a href="mailto:<?= esc($judge['email']) ?>"><?= esc($judge['email']) ?></a>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4"><strong><i class="bi bi-shield-check"></i> Role:</strong></div>
                    <div class="col-md-8">
                        <span class="badge bg-info"><?= esc($judge['role_display_name']) ?></span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4"><strong><i class="bi bi-toggle-on"></i> Status:</strong></div>
                    <div class="col-md-8">
                        <?php if ($judge['status'] === 'active'): ?>
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Active
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary">
                                <i class="bi bi-x-circle"></i> Inactive
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4"><strong><i class="bi bi-calendar-plus"></i> Created At:</strong></div>
                    <div class="col-md-8">
                        <?= date('F d, Y h:i A', strtotime($judge['created_at'])) ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4"><strong><i class="bi bi-clock-history"></i> Last Updated:</strong></div>
                    <div class="col-md-8">
                        <?= $judge['updated_at'] ? date('F d, Y h:i A', strtotime($judge['updated_at'])) : 'Never' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions Card -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('admin/judges/edit/' . $judge['id']) ?>" 
                       class="btn btn-warning">
                        <i class="bi bi-pencil-fill"></i> Edit Judge
                    </a>
                    
                    <button 
                        onclick="confirmDelete(<?= $judge['id'] ?>, '<?= esc($judge['username']) ?>')"
                        class="btn btn-danger">
                        <i class="bi bi-trash-fill"></i> Delete Judge
                    </button>
                    
                    <?php if ($judge['status'] === 'active'): ?>
                        <a href="<?= base_url('admin/judges/edit/' . $judge['id']) ?>" 
                           class="btn btn-secondary">
                            <i class="bi bi-ban"></i> Deactivate Account
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('admin/judges/edit/' . $judge['id']) ?>" 
                           class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Activate Account
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Stats Card -->
        <div class="card shadow-sm bg-light">
            <div class="card-body text-center">
                <i class="bi bi-star-fill text-warning" style="font-size: 48px;"></i>
                <h6 class="mt-3 mb-1">Judge Account</h6>
                <p class="text-muted small mb-0">
                    Can view contestants and submit scores during pageant events
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(judgeId, username) {
    if (confirm(`Are you sure you want to delete judge "${username}"?\n\nThis action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('admin/judges/delete/') ?>' + judgeId;
        
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
