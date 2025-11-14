<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Add New Judge<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-person-plus-fill"></i> Add New Judge</h1>
        <p class="text-muted">Create a new judge account</p>
    </div>
    <a href="<?= base_url('admin/judges') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Validation Errors -->
<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Form Card -->
<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="<?= base_url('admin/judges/store') ?>" method="post" autocomplete="off">
            <?= csrf_field() ?>

            <!-- Decoy fields to defeat browser autofill -->
            <input type="text" name="fake_email" autocomplete="username" tabindex="-1" class="offscreen-input">
            <input type="password" name="fake_password" autocomplete="new-password" tabindex="-1" class="offscreen-input">
            
            <div class="row">
                <!-- Full Name -->
                <div class="col-12 mb-3">
                    <label for="full_name" class="form-label">
                        <i class="bi bi-person-fill"></i> Full Name <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="full_name" 
                        name="full_name" 
                        value="<?= old('full_name') ?>"
                        required
                        placeholder="Enter full name"
                    >
                </div>
            </div>
            
            <div class="row">
                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope-fill"></i> Email Address <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        value="<?= old('email') ?>"
                        required
                        placeholder="Enter Email Address"
                        autocomplete="off" autocapitalize="none" autocorrect="off" spellcheck="false"
                        inputmode="email"
                    >
                </div>
                
                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock-fill"></i> Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            required
                            minlength="6"
                            placeholder="Enter password"
                            autocomplete="new-password"
                        >
                        <button 
                            class="btn btn-outline-secondary" 
                            type="button" 
                            onclick="togglePassword()"
                            title="Show/Hide Password"
                        >
                            <i class="bi bi-eye-fill" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="form-label">
                    <i class="bi bi-toggle-on"></i> Account Status
                </label>
                <select class="form-select" id="status" name="status">
                    <option value="active" selected>Active - Can login and score</option>
                    <option value="inactive">Inactive - Cannot login</option>
                </select>
            </div>

            <hr class="my-4">

            <h5 class="fw-bold mb-3"><i class="bi bi-award-fill"></i> Judge Introduction Profile</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="judge_title" class="form-label">
                        <i class="bi bi-briefcase-fill"></i> Professional Title / Expertise
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="judge_title" 
                        name="judge_title" 
                        value="<?= old('judge_title') ?>"
                        placeholder="e.g., International Fashion Designer"
                    >
                </div>
                <div class="col-md-6 mb-3">
                    <label for="judge_organization" class="form-label">
                        <i class="bi bi-building"></i> Organization / Affiliation
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="judge_organization" 
                        name="judge_organization" 
                        value="<?= old('judge_organization') ?>"
                        placeholder="e.g., Founder, Inspire Creative Studios"
                    >
                </div>
            </div>

            <div class="mb-3">
                <label for="judge_achievements" class="form-label">
                    <i class="bi bi-trophy-fill"></i> Notable Achievements & Awards
                </label>
                <textarea class="form-control" id="judge_achievements" name="judge_achievements" rows="3" placeholder="Highlight recognitions, awards, publications, or milestones. Use sentences or bullet-style phrases."><?= old('judge_achievements') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="judge_biography" class="form-label">
                    <i class="bi bi-journal-text"></i> Brief Biography
                </label>
                <textarea class="form-control" id="judge_biography" name="judge_biography" rows="3" placeholder="Share the judge's background, advocacies, and passions."><?= old('judge_biography') ?></textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Create Judge Account
                </button>
                <a href="<?= base_url('admin/judges') ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'bi bi-eye-slash-fill';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'bi bi-eye-fill';
    }
}
// Clear autofilled values on first load if no old input exists
document.addEventListener('DOMContentLoaded', function() {
    var hadOldEmail = '<?= old('email') ? '1' : '0' ?>' === '1';
    var hadOldPwd   = '<?= old('password') ? '1' : '0' ?>' === '1';
    var email = document.getElementById('email');
    var pwd   = document.getElementById('password');
    if (email && !hadOldEmail) { email.value = ''; }
    if (pwd && !hadOldPwd)     { pwd.value   = ''; }
});
</script>
<?= $this->endSection() ?>
