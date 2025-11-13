<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Contestant Details<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .profile-photo {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 15px;
        border: 3px solid #ddd;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .info-label {
        font-weight: 600;
        color: #667eea;
        margin-bottom: 5px;
    }
    
    .info-value {
        font-size: 16px;
        margin-bottom: 20px;
    }

    @media print {
        .no-print,
        .sidebar,
        header,
        nav {
            display: none !important;
        }

        body {
            background: #ffffff;
            font-size: 12px;
        }

        .container-fluid,
        .container {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #d1d5db !important;
            margin-bottom: 12px !important;
        }

        .card-body {
            padding: 18px !important;
        }

        .card-header {
            background: #f1f5f9 !important;
            color: #0f172a !important;
            border-bottom: 1px solid #d1d5db !important;
            padding: 12px 18px !important;
        }

        .info-label {
            color: #0f172a !important;
            margin-bottom: 4px !important;
        }

        .info-value {
            margin-bottom: 12px !important;
            color: #1f2937 !important;
        }

        a[href]:after {
            content: "";
        }

        .profile-photo {
            border: 1px solid #d1d5db !important;
            box-shadow: none !important;
        }

        @page {
            margin: 15mm;
        }

        .page-break {
            page-break-after: always;
        }

        .print-layout {
            max-width: 900px;
            margin: 0 auto;
        }
    }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-eye-fill"></i> Contestant Details</h1>
        <p class="text-muted">View contestant information</p>
    </div>
    <div class="d-flex gap-2 no-print">
        <a href="<?= base_url('admin/contestants') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
        <button type="button" class="btn btn-outline-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Print
        </button>
    </div>
</div>

<div class="print-layout">
<div class="row g-4">
    <!-- Photo & Basic Info -->
    <div class="col-lg-4 col-xl-3">
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center">
                <?php if (!empty($contestant['profile_picture'])): ?>
                    <img src="<?= base_url('uploads/contestants/' . $contestant['profile_picture']) ?>" 
                         alt="<?= esc($contestant['first_name']) ?>" 
                         class="profile-photo mb-3">
                <?php else: ?>
                    <div class="profile-photo mx-auto mb-3 d-flex align-items-center justify-content-center" 
                         style="background: #f0f0f0;">
                        <i class="bi bi-person" style="font-size: 80px; color: #999;"></i>
                    </div>
                <?php endif; ?>
                
                <h3 class="mb-1">
                    <?= esc($contestant['first_name']) ?> 
                    <?= !empty($contestant['middle_name']) ? esc($contestant['middle_name'][0]) . '.' : '' ?> 
                    <?= esc($contestant['last_name']) ?>
                </h3>
                <p class="text-muted mb-2">Contestant <?= esc($contestant['contestant_number']) ?></p>
                
                <?php
                $statusClass = match($contestant['status']) {
                    'active' => 'success',
                    'inactive' => 'secondary',
                    'disqualified' => 'danger',
                    default => 'secondary'
                };
                ?>
                <span class="badge bg-<?= $statusClass ?> fs-6">
                    <?= ucfirst($contestant['status']) ?>
                </span>
            </div>
        </div>
    </div>
    
    <!-- Personal Information -->
    <div class="col-lg-8 col-xl-9">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">First Name</div>
                        <div class="info-value"><?= esc($contestant['first_name']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Middle Name</div>
                        <div class="info-value"><?= !empty($contestant['middle_name']) ? esc($contestant['middle_name']) : '<em class="text-muted">Not provided</em>' ?></div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Last Name</div>
                        <div class="info-value"><?= esc($contestant['last_name']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Gender</div>
                        <div class="info-value">
                            <i class="bi bi-gender-<?= strtolower($contestant['gender']) === 'male' ? 'male' : 'female' ?>"></i>
                            <?= esc($contestant['gender']) ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Birthdate</div>
                        <div class="info-value">
                            <i class="bi bi-calendar"></i> <?= date('F d, Y', strtotime($contestant['birthdate'])) ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Age</div>
                        <div class="info-value"><?= !empty($contestant['age']) ? $contestant['age'] . ' years old' : 'N/A' ?></div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Height</div>
                        <div class="info-value"><?= !empty($contestant['height']) ? esc($contestant['height']) : '<em class="text-muted">Not provided</em>' ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Weight</div>
                        <div class="info-value"><?= !empty($contestant['weight']) ? esc($contestant['weight']) : '<em class="text-muted">Not provided</em>' ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact Information -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-telephone-fill"></i> Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="info-label">Address</div>
                <div class="info-value"><?= !empty($contestant['address']) ? esc($contestant['address']) : 'N/A' ?></div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">City</div>
                        <div class="info-value"><?= !empty($contestant['city']) ? esc($contestant['city']) : 'N/A' ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Province</div>
                        <div class="info-value"><?= !empty($contestant['province']) ? esc($contestant['province']) : 'N/A' ?></div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Contact Number</div>
                        <div class="info-value">
                            <i class="bi bi-phone"></i> 
                            <a href="tel:<?= !empty($contestant['contact_number']) ? esc($contestant['contact_number']) : '' ?>">
                                <?= !empty($contestant['contact_number']) ? esc($contestant['contact_number']) : 'N/A' ?>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <?php if (!empty($contestant['email'])): ?>
                                <i class="bi bi-envelope"></i> 
                                <a href="mailto:<?= esc($contestant['email']) ?>"><?= esc($contestant['email']) ?></a>
                            <?php else: ?>
                                <em class="text-muted">Not provided</em>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Information -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-stars"></i> Additional Information</h5>
            </div>
            <div class="card-body">
                <div class="info-label">Talent/Special Skill</div>
                <div class="info-value"><?= !empty($contestant['talent']) ? esc($contestant['talent']) : '<em class="text-muted">Not provided</em>' ?></div>
                
                <div class="info-label">Advocacy/Platform</div>
                <div class="info-value"><?= !empty($contestant['advocacy']) ? nl2br(esc($contestant['advocacy'])) : '<em class="text-muted">Not provided</em>' ?></div>
                
                <div class="info-label">Hobbies/Interests</div>
                <div class="info-value"><?= !empty($contestant['hobbies']) ? nl2br(esc($contestant['hobbies'])) : '<em class="text-muted">Not provided</em>' ?></div>
                
                <div class="info-label">Educational Background</div>
                <div class="info-value"><?= !empty($contestant['education']) ? nl2br(esc($contestant['education'])) : '<em class="text-muted">Not provided</em>' ?></div>
            </div>
        </div>
    </div>
</div>
</div>

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
