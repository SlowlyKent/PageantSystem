<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Judge Details<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom d-print-none">
    <div>
        <h1 class="h2"><i class="bi bi-eye-fill"></i> Judge Details</h1>
        <p class="text-muted">View judge account information</p>
    </div>
    <div class="d-flex gap-2 no-print">
        <a href="<?= base_url('admin/judges') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
        <button type="button" class="btn btn-outline-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Profile
        </button>
    </div>
</div>

<!-- Judge Information Card -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm mb-3 d-print-none">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> Account Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong><i class="bi bi-hash"></i> ID:</strong></div>
                    <div class="col-md-8"><?= esc($judge['id']) ?></div>
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
</div>

<!-- Judge Profile Card -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex align-items-center gap-2">
                <i class="bi bi-award-fill text-primary"></i>
                <h5 class="mb-0">Judge Introduction Profile</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted text-uppercase">Professional Title / Expertise</h6>
                        <p class="mb-0"><?= esc($judge['judge_title'] ?? 'Not specified') ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted text-uppercase">Organization / Affiliation</h6>
                        <p class="mb-0"><?= esc($judge['judge_organization'] ?? 'Not specified') ?></p>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted text-uppercase">Notable Achievements &amp; Awards</h6>
                    <?php if (!empty($judge['judge_achievements'])): ?>
                        <div class="p-3 rounded bg-light border">
                            <?= nl2br(esc($judge['judge_achievements'])) ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted fst-italic">No achievements provided.</p>
                    <?php endif; ?>
                </div>

                <div>
                    <h6 class="text-muted text-uppercase">Brief Biography</h6>
                    <?php if (!empty($judge['judge_biography'])): ?>
                        <div class="p-3 rounded bg-light border">
                            <?= nl2br(esc($judge['judge_biography'])) ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted fst-italic">No biography provided.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
