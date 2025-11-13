<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Edit Round<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-pencil-square"></i> Edit Round</h1>
        <p class="text-muted">Update round settings and configuration</p>
    </div>
    <a href="<?= base_url('admin/rounds-criteria') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<!-- Edit Form -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="<?= base_url('admin/rounds-criteria/update/' . $round['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="round_name" class="form-label">Round Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="round_name" name="round_name" value="<?= esc($round['round_name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= esc($round['description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="round_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="round_order" name="round_order" value="<?= $round['round_order'] ?>" min="1" required>
                        <small class="form-text text-muted">Lower numbers appear first</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_elimination" name="is_elimination" value="1" <?= $round['is_elimination'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_elimination">
                                <strong>Elimination Round</strong>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3" id="quota_section" style="display: <?= $round['is_elimination'] ? 'block' : 'none' ?>;">
                        <label for="elimination_quota" class="form-label">Top N to Advance</label>
                        <input type="number" class="form-control" id="elimination_quota" name="elimination_quota" value="<?= $round['elimination_quota'] ?? '' ?>" min="1">
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_final" name="is_final" value="1" <?= $round['is_final'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_final">
                                <strong>Final Round</strong>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('admin/rounds-criteria') ?>" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Round Status</h6>
                <div class="mb-2">
                    <strong>Status:</strong>
                    <span class="badge bg-<?= $round['is_locked'] ? 'danger' : ($round['status'] === 'active' ? 'success' : 'secondary') ?>">
                        <?= $round['is_locked'] ? 'Locked' : ucfirst($round['status']) ?>
                    </span>
                </div>
                <div class="mb-2">
                    <strong>Round Number:</strong> <?= $round['round_number'] ?>
                </div>
                <?php if ($round['is_locked']): ?>
                    <form action="<?= base_url('admin/rounds-criteria/unlock/' . $round['id']) ?>" method="post">
                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Unlock this round?')">
                            <i class="bi bi-unlock"></i> Unlock
                        </button>
                    </form>
                <?php else: ?>
                    <form action="<?= base_url('admin/rounds-criteria/lock/' . $round['id']) ?>" method="post">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Lock this round?')">
                            <i class="bi bi-lock"></i> Lock
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('is_elimination').addEventListener('change', function() {
    document.getElementById('quota_section').style.display = this.checked ? 'block' : 'none';
});
</script>

<?= $this->endSection() ?>