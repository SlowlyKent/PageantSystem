<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>System Settings<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Settings Page Styles */
    .settings-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 25px;
    }
    
    .settings-header {
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    
    .settings-header h5 {
        color: #333;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .color-picker-group {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    
    .color-preview {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        border: 2px solid #ddd;
        cursor: pointer;
    }
    
    .color-picker-group input[type="color"] {
        width: 60px;
        height: 50px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }
    
    .theme-preset-btn {
        padding: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        background: white;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }
    
    .theme-preset-btn:hover {
        border-color: #667eea;
        background: #f8f9ff;
    }
    
    .theme-preset-btn.active {
        border-color: #667eea;
        background: #667eea;
        color: white;
    }
    
    .theme-preview-box {
        margin-top: 20px;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 15px;
    }
    
    .preview-button {
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }
    
    .logo-preview {
        max-width: 200px;
        max-height: 100px;
        border-radius: 10px;
        border: 2px dashed #ddd;
        padding: 10px;
    }
    
    .logo-preview-container {
        position: relative;
        display: inline-block;
    }
    
    .remove-logo-btn {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #dc3545;
        color: white;
        border: 2px solid white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .remove-logo-btn:hover {
        background: #c82333;
        transform: scale(1.1);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-gear-fill"></i> System Settings</h1>
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

<div class="row">
    <!-- Left Column: Settings Forms -->
    <div class="col-lg-7">
        
        <!-- General Settings Card -->
        <div class="settings-card">
            <div class="settings-header">
                <h5><i class="bi bi-house-fill"></i> General Settings</h5>
            </div>
            
            <!-- FORM 1: General Settings (System Name & Logo) -->
            <!-- NOTE: enctype="multipart/form-data" is REQUIRED for file uploads -->
            <form action="<?= base_url('admin/settings/update-general') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <!-- System Name -->
                <div class="mb-3">
                    <label for="system_name" class="form-label">
                        <i class="bi bi-tag-fill"></i> System Name
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="system_name" 
                        name="system_name" 
                        value="<?= esc($settings['system_name'] ?? 'Pageant System') ?>"
                        required
                    >
                    <small class="text-muted">This name appears in the browser tab and header</small>
                </div>
                
                <!-- Logo Upload -->
                <div class="mb-3">
                    <label for="logo" class="form-label">
                        <i class="bi bi-image-fill"></i> System Logo
                    </label>
                    
                    <!-- Show current logo if exists -->
                    <?php if (!empty($settings['logo'])): ?>
                        <div class="mb-2">
                            <div class="logo-preview-container">
                                <img 
                                    src="<?= base_url('uploads/settings/' . $settings['logo']) ?>" 
                                    alt="Current Logo" 
                                    class="logo-preview"
                                    id="currentLogo"
                                >
                                <button 
                                    type="button" 
                                    class="remove-logo-btn" 
                                    onclick="removeLogo()"
                                    title="Remove logo"
                                >
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <p class="small text-muted mt-1">Current logo - Click X to remove</p>
                        </div>
                    <?php endif; ?>
                    
                    <input 
                        type="file" 
                        class="form-control" 
                        id="logo" 
                        name="logo" 
                        accept="image/jpeg,image/png,image/gif"
                    >
                    <small class="text-muted">Allowed: JPG, PNG, GIF (Max 2MB)</small>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save General Settings
                </button>
            </form>
        </div>

        <!-- Theme Settings Card -->
        <div class="settings-card">
            <div class="settings-header">
                <h5><i class="bi bi-palette-fill"></i> Theme Settings</h5>
            </div>
            
            <!-- Theme Presets (Quick Selection) -->
            <div class="mb-4">
                <label class="form-label"><i class="bi bi-stars"></i> Quick Theme Presets</label>
                <div class="row g-3">
                    <div class="col-3">
                        <div class="theme-preset-btn" data-preset="classic">
                            <i class="bi bi-gem" style="font-size: 24px;"></i>
                            <div class="mt-2"><strong>Classic</strong></div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="theme-preset-btn" data-preset="modern">
                            <i class="bi bi-lightning-fill" style="font-size: 24px;"></i>
                            <div class="mt-2"><strong>Modern</strong></div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="theme-preset-btn" data-preset="youthful">
                            <i class="bi bi-sun-fill" style="font-size: 24px;"></i>
                            <div class="mt-2"><strong>Youthful</strong></div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="theme-preset-btn" data-preset="elegant">
                            <i class="bi bi-award-fill" style="font-size: 24px;"></i>
                            <div class="mt-2"><strong>Elegant</strong></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FORM 2: Theme Settings (Colors & Background) -->
            <form action="<?= base_url('admin/settings/update-theme') ?>" method="post" enctype="multipart/form-data" id="themeForm">
                <?= csrf_field() ?>
                
                <!-- Hidden field for selected preset -->
                <input type="hidden" name="theme_preset" id="theme_preset" value="<?= esc($settings['theme_preset'] ?? 'classic') ?>">
                
                <!-- Color Pickers -->
                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-droplet-fill"></i> Theme Colors</label>
                    
                    <!-- Primary Color -->
                    <div class="color-picker-group">
                        <input 
                            type="color" 
                            id="primary_color" 
                            name="primary_color" 
                            value="<?= esc($settings['primary_color'] ?? '#667eea') ?>"
                        >
                        <div>
                            <strong>Primary Color</strong>
                            <p class="small text-muted mb-0">Main brand color</p>
                        </div>
                    </div>
                    
                    <!-- Accent Color -->
                    <div class="color-picker-group">
                        <input 
                            type="color" 
                            id="accent_color" 
                            name="accent_color" 
                            value="<?= esc($settings['accent_color'] ?? '#764ba2') ?>"
                        >
                        <div>
                            <strong>Accent Color</strong>
                            <p class="small text-muted mb-0">Secondary highlights</p>
                        </div>
                    </div>
                    
                    <!-- Text Color -->
                    <div class="color-picker-group">
                        <input 
                            type="color" 
                            id="text_color" 
                            name="text_color" 
                            value="<?= esc($settings['text_color'] ?? '#333333') ?>"
                        >
                        <div>
                            <strong>Text Color</strong>
                            <p class="small text-muted mb-0">Main text color</p>
                        </div>
                    </div>
                </div>
                
                <!-- Background Type -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-image"></i> Background Type</label>
                    <select class="form-select" name="background_type" id="background_type">
                        <option value="gradient" <?= ($settings['background_type'] ?? 'gradient') == 'gradient' ? 'selected' : '' ?>>Gradient (Primary + Accent)</option>
                        <option value="solid" <?= ($settings['background_type'] ?? '') == 'solid' ? 'selected' : '' ?>>Solid Color</option>
                        <option value="image" <?= ($settings['background_type'] ?? '') == 'image' ? 'selected' : '' ?>>Background Image</option>
                    </select>
                </div>
                
                <!-- Background Color (shown when solid is selected) -->
                <div class="mb-3" id="bg_color_section" style="display: none;">
                    <label for="background_color" class="form-label">Background Color</label>
                    <input 
                        type="color" 
                        class="form-control" 
                        id="background_color" 
                        name="background_color" 
                        value="<?= esc($settings['background_color'] ?? '#f8f9fa') ?>"
                    >
                </div>
                
                <!-- Background Image (shown when image is selected) -->
                <div class="mb-3" id="bg_image_section" style="display: none;">
                    <label for="background_image" class="form-label">Background Image</label>
                    <input 
                        type="file" 
                        class="form-control" 
                        id="background_image" 
                        name="background_image" 
                        accept="image/jpeg,image/png"
                    >
                    <small class="text-muted">Recommended: 1920x1080px</small>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Theme Settings
                </button>
            </form>
        </div>
        
    </div>
    
    <!-- Right Column: Live Preview -->
    <div class="col-lg-5">
        <div class="settings-card position-sticky" style="top: 20px;">
            <div class="settings-header">
                <h5><i class="bi bi-eye-fill"></i> Live Preview</h5>
            </div>
            
            <!-- Preview Box (changes colors in real-time) -->
            <div class="theme-preview-box" id="previewBox">
                <h3 id="previewText">Preview Theme</h3>
                <button class="preview-button" id="previewBtn">Sample Button</button>
                <p id="previewDescription">This is how your theme will look</p>
            </div>
            
            <div class="mt-3 p-3 bg-light rounded">
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> 
                    <strong>Tip:</strong> Colors update in real-time! Try clicking preset buttons or adjusting color pickers above.
                </small>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/**
 * BEGINNER-FRIENDLY JAVASCRIPT
 * This script provides live preview of theme changes
 */

// Wait for page to load completely
document.addEventListener('DOMContentLoaded', function() {
    
    // Get all color inputs
    const primaryColor = document.getElementById('primary_color');
    const accentColor = document.getElementById('accent_color');
    const textColor = document.getElementById('text_color');
    const backgroundType = document.getElementById('background_type');
    const backgroundColor = document.getElementById('background_color');
    
    // Get preview elements
    const previewBox = document.getElementById('previewBox');
    const previewBtn = document.getElementById('previewBtn');
    const previewText = document.getElementById('previewText');
    const previewDescription = document.getElementById('previewDescription');
    
    /**
     * Update preview colors
     * This function applies the selected colors to the preview box
     */
    function updatePreview() {
        const primary = primaryColor.value;
        const accent = accentColor.value;
        const text = textColor.value;
        const bgType = backgroundType.value;
        
        // Update preview box background
        if (bgType === 'gradient') {
            previewBox.style.background = `linear-gradient(135deg, ${primary} 0%, ${accent} 100%)`;
        } else if (bgType === 'solid') {
            previewBox.style.background = backgroundColor.value;
        }
        
        // Update button colors
        previewBtn.style.background = primary;
        previewBtn.style.color = 'white';
        
        // Update text colors
        previewText.style.color = bgType === 'gradient' ? 'white' : text;
        previewDescription.style.color = bgType === 'gradient' ? 'rgba(255,255,255,0.9)' : text;
    }
    
    /**
     * Show/hide background options based on type
     */
    function toggleBackgroundOptions() {
        const bgType = backgroundType.value;
        const colorSection = document.getElementById('bg_color_section');
        const imageSection = document.getElementById('bg_image_section');
        
        // Hide all sections first
        colorSection.style.display = 'none';
        imageSection.style.display = 'none';
        
        // Show relevant section
        if (bgType === 'solid') {
            colorSection.style.display = 'block';
        } else if (bgType === 'image') {
            imageSection.style.display = 'block';
        }
        
        updatePreview();
    }
    
    /**
     * Apply theme preset
     * When user clicks a preset button, apply those colors
     */
    function applyPreset(preset) {
        // Theme preset colors
        const presets = {
            classic: {
                primary: '#667eea',
                accent: '#764ba2',
                text: '#333333'
            },
            modern: {
                primary: '#6366f1',
                accent: '#ec4899',
                text: '#1f2937'
            },
            youthful: {
                primary: '#f59e0b',
                accent: '#10b981',
                text: '#374151'
            },
            elegant: {
                primary: '#8b5cf6',
                accent: '#d946ef',
                text: '#1e293b'
            }
        };
        
        // Check if preset exists
        if (presets[preset]) {
            // Update color inputs
            primaryColor.value = presets[preset].primary;
            accentColor.value = presets[preset].accent;
            textColor.value = presets[preset].text;
            
            // Update hidden field
            document.getElementById('theme_preset').value = preset;
            
            // Update preview
            updatePreview();
            
            // Update active button
            document.querySelectorAll('.theme-preset-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.closest('.theme-preset-btn').classList.add('active');
        }
    }
    
    // Event Listeners (when user interacts with inputs)
    primaryColor.addEventListener('input', updatePreview);
    accentColor.addEventListener('input', updatePreview);
    textColor.addEventListener('input', updatePreview);
    backgroundType.addEventListener('change', toggleBackgroundOptions);
    backgroundColor.addEventListener('input', updatePreview);
    
    // Preset buttons click handler
    document.querySelectorAll('.theme-preset-btn').forEach(button => {
        button.addEventListener('click', function() {
            const preset = this.getAttribute('data-preset');
            applyPreset(preset);
        });
    });
    
    // Initialize on page load
    updatePreview();
    toggleBackgroundOptions();
    
    // Mark current preset as active
    const currentPreset = document.getElementById('theme_preset').value;
    document.querySelector(`[data-preset="${currentPreset}"]`)?.classList.add('active');
});

/**
 * Remove logo function
 * Sends AJAX request to delete logo from server
 */
function removeLogo() {
    // Confirm with user
    if (!confirm('Are you sure you want to remove the logo?')) {
        return;
    }
    
    // Show loading state
    const logoContainer = document.querySelector('.logo-preview-container').parentElement;
    logoContainer.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p>Removing logo...</p></div>';
    
    // Send AJAX request to remove logo
    fetch('<?= base_url('admin/settings/remove-logo') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the logo preview section
            logoContainer.remove();
            
            // Show success message
            alert('Logo removed successfully!');
            
            // Reload page to update navbar
            location.reload();
        } else {
            alert('Error removing logo: ' + (data.message || 'Unknown error'));
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error removing logo. Please try again.');
        location.reload();
    });
}
</script>
<?= $this->endSection() ?>
