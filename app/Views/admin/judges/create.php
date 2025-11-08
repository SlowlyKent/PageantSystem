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
        <form action="<?= base_url('admin/judges/store') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="row">
                <!-- Username -->
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">
                        <i class="bi bi-person-circle"></i> Username <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="username" 
                        name="username" 
                        value="<?= old('username') ?>"
                        required
                        placeholder="Enter username"
                    >
                    <small class="text-muted">Used for login. Must be unique.</small>
                </div>
                
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
                        placeholder="judge@example.com"
                    >
                    <small class="text-muted">Must be a valid email address.</small>
                </div>
            </div>
            
            <div class="row">
                <!-- Full Name -->
                <div class="col-md-6 mb-3">
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
                        placeholder="John Doe"
                    >
                    <small class="text-muted">Judge's complete name.</small>
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
                            placeholder="Minimum 6 characters"
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
                    <small class="text-muted">Minimum 6 characters. Judge will use this to login.</small>
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
                <small class="text-muted">Set to "Inactive" to temporarily disable the account.</small>
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

<!-- Help Card -->
<div class="card mt-3 bg-light">
    <div class="card-body">
        <h6 class="card-title"><i class="bi bi-info-circle-fill"></i> Tips</h6>
        <ul class="mb-0 small">
            <li>All fields marked with <span class="text-danger">*</span> are required.</li>
            <li>Username and email must be unique (not used by other accounts).</li>
            <li>Password will be securely hashed and stored.</li>
            <li>Judge can login using username or email with their password.</li>
            <li>You can edit judge information anytime from the judges list.</li>
        </ul>
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
</script>
<?= $this->endSection() ?>
