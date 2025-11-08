<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Add New Contestant<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .photo-preview {
        width: 150px;
        height: 150px;
        border: 2px dashed #ddd;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .photo-preview:hover {
        border-color: #667eea;
        background: #f0f0ff;
    }
    
    .photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .photo-preview-placeholder {
        text-align: center;
        color: #999;
    }
    
    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        margin-top: 25px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-person-plus-fill"></i> Add New Contestant</h1>
        <p class="text-muted">Register a new contestant to the pageant</p>
    </div>
    <a href="<?= base_url('admin/contestants') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

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
        <form action="<?= base_url('admin/contestants/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <!-- Basic Information Section -->
            <div class="section-header">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> Basic Information</h5>
            </div>
            
            <div class="row">
                <!-- Contestant Number -->
                <div class="col-md-3 mb-3">
                    <label for="contestant_number" class="form-label">
                        Contestant # <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="contestant_number" 
                        name="contestant_number" 
                        value="<?= old('contestant_number', $contestant_number) ?>"
                        required
                        readonly
                        style="background: #f0f0f0;"
                    >
                    <small class="text-muted">Auto-generated</small>
                </div>
                
                <!-- First Name -->
                <div class="col-md-3 mb-3">
                    <label for="first_name" class="form-label">
                        First Name <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="first_name" 
                        name="first_name" 
                        value="<?= old('first_name') ?>"
                        required
                    >
                </div>
                
                <!-- Middle Name -->
                <div class="col-md-3 mb-3">
                    <label for="middle_name" class="form-label">
                        Middle Name
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="middle_name" 
                        name="middle_name" 
                        value="<?= old('middle_name') ?>"
                    >
                    <small class="text-muted">Optional</small>
                </div>
                
                <!-- Last Name -->
                <div class="col-md-3 mb-3">
                    <label for="last_name" class="form-label">
                        Last Name <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="last_name" 
                        name="last_name" 
                        value="<?= old('last_name') ?>"
                        required
                    >
                </div>
            </div>
            
            <div class="row">
                <!-- Birthdate -->
                <div class="col-md-3 mb-3">
                    <label for="birthdate" class="form-label">
                        Birthdate <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="date" 
                        class="form-control" 
                        id="birthdate" 
                        name="birthdate" 
                        value="<?= old('birthdate') ?>"
                        required
                        onchange="calculateAge()"
                    >
                </div>
                
                <!-- Age -->
                <div class="col-md-2 mb-3">
                    <label for="age" class="form-label">
                        Age <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="age" 
                        name="age" 
                        value="<?= old('age') ?>"
                        required
                        style="background: #fffacd;"
                    >
                    <small class="text-muted">Auto-calculated from birthdate</small>
                </div>
                
                <!-- Gender -->
                <div class="col-md-2 mb-3">
                    <label for="gender" class="form-label">
                        Gender <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="gender" name="gender" required>
                        <option value="">Select...</option>
                        <option value="Male" <?= old('gender') === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= old('gender') === 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
                
                <!-- Height -->
                <div class="col-md-2 mb-3">
                    <label for="height" class="form-label">Height</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="height" 
                        name="height" 
                        value="<?= old('height') ?>"
                        placeholder="e.g., 170cm"
                    >
                </div>
                
                <!-- Weight -->
                <div class="col-md-3 mb-3">
                    <label for="weight" class="form-label">Weight</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="weight" 
                        name="weight" 
                        value="<?= old('weight') ?>"
                        placeholder="e.g., 55kg"
                    >
                </div>
            </div>
            
            <!-- Contact Information Section -->
            <div class="section-header">
                <h5 class="mb-0"><i class="bi bi-telephone-fill"></i> Contact Information</h5>
            </div>
            
            <div class="row">
                <!-- Address -->
                <div class="col-md-12 mb-3">
                    <label for="address" class="form-label">
                        Address <span class="text-danger">*</span>
                    </label>
                    <textarea 
                        class="form-control" 
                        id="address" 
                        name="address" 
                        rows="2" 
                        required
                    ><?= old('address') ?></textarea>
                </div>
            </div>
            
            <div class="row">
                <!-- City -->
                <div class="col-md-4 mb-3">
                    <label for="city" class="form-label">
                        City <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="city" 
                        name="city" 
                        value="<?= old('city') ?>"
                        required
                    >
                </div>
                
                <!-- Province -->
                <div class="col-md-4 mb-3">
                    <label for="province" class="form-label">
                        Province <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="province" 
                        name="province" 
                        value="<?= old('province') ?>"
                        required
                    >
                </div>
                
                <!-- Contact Number -->
                <div class="col-md-4 mb-3">
                    <label for="contact_number" class="form-label">
                        Contact Number <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="contact_number" 
                        name="contact_number" 
                        value="<?= old('contact_number') ?>"
                        required
                        placeholder="09XX-XXX-XXXX"
                    >
                </div>
            </div>
            
            <div class="row">
                <!-- Email -->
                <div class="col-md-12 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        value="<?= old('email') ?>"
                        placeholder="contestant@example.com"
                    >
                    <small class="text-muted">Optional</small>
                </div>
            </div>
            
            <!-- Additional Information Section -->
            <div class="section-header">
                <h5 class="mb-0"><i class="bi bi-stars"></i> Additional Information</h5>
            </div>
            
            <div class="row">
                <!-- Profile Picture -->
                <div class="col-md-3 mb-3">
                    <label for="profile_picture" class="form-label">
                        Profile Picture
                    </label>
                    <div class="photo-preview" onclick="document.getElementById('profile_picture').click()">
                        <div class="photo-preview-placeholder" id="photoPreview">
                            <i class="bi bi-camera" style="font-size: 40px;"></i>
                            <p class="mb-0 small">Click to upload</p>
                        </div>
                    </div>
                    <input 
                        type="file" 
                        class="form-control d-none" 
                        id="profile_picture" 
                        name="profile_picture" 
                        accept="image/*"
                        onchange="previewPhoto(this)"
                    >
                    <small class="text-muted">Max 2MB (JPG, PNG)</small>
                </div>
                
                <div class="col-md-9">
                    <div class="row">
                        <!-- Talent -->
                        <div class="col-md-6 mb-3">
                            <label for="talent" class="form-label">Talent/Special Skill</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="talent" 
                                name="talent" 
                                value="<?= old('talent') ?>"
                                placeholder="e.g., Singing, Dancing"
                            >
                        </div>
                        
                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Advocacy -->
                <div class="col-md-12 mb-3">
                    <label for="advocacy" class="form-label">Advocacy/Platform</label>
                    <textarea 
                        class="form-control" 
                        id="advocacy" 
                        name="advocacy" 
                        rows="3"
                        placeholder="What cause or advocacy does this contestant support?"
                    ><?= old('advocacy') ?></textarea>
                </div>
            </div>
            
            <div class="row">
                <!-- Hobbies -->
                <div class="col-md-6 mb-3">
                    <label for="hobbies" class="form-label">Hobbies/Interests</label>
                    <textarea 
                        class="form-control" 
                        id="hobbies" 
                        name="hobbies" 
                        rows="3"
                    ><?= old('hobbies') ?></textarea>
                </div>
                
                <!-- Education -->
                <div class="col-md-6 mb-3">
                    <label for="education" class="form-label">Educational Background</label>
                    <textarea 
                        class="form-control" 
                        id="education" 
                        name="education" 
                        rows="3"
                        placeholder="School, degree, achievements..."
                    ><?= old('education') ?></textarea>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle"></i> Add Contestant
                </button>
                <a href="<?= base_url('admin/contestants') ?>" class="btn btn-secondary btn-lg">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Calculate age from birthdate
function calculateAge() {
    const birthdate = document.getElementById('birthdate').value;
    if (birthdate) {
        const today = new Date();
        const birth = new Date(birthdate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        
        document.getElementById('age').value = age;
    }
}

// Preview photo before upload
function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?= $this->endSection() ?>
