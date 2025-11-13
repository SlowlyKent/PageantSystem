<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>System Settings<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Google Fonts for Font Preview -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Cinzel:wght@400;700;900&family=Cormorant+Garamond:wght@400;700&family=Lora:wght@400;700&family=Merriweather:wght@400;700;900&family=EB+Garamond:wght@400;700&family=Montserrat:wght@400;700;900&family=Raleway:wght@400;700&display=swap" rel="stylesheet">
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
                
                <!-- Title Font Selection -->
                <div class="mb-3">
                    <label for="title_font" class="form-label">
                        <i class="bi bi-fonts"></i> Pageant Title Font
                    </label>
                    <select 
                        class="form-select" 
                        id="title_font" 
                        name="title_font"
                        onchange="updateFontPreview(this.value)"
                    >
                        <optgroup label="Elegant & Sophisticated">
                            <option value="'Playfair Display', serif" <?= ($settings['title_font'] ?? '') === "'Playfair Display', serif" ? 'selected' : '' ?>>Playfair Display (Royal & Elegant)</option>
                            <option value="'Cinzel', serif" <?= ($settings['title_font'] ?? '') === "'Cinzel', serif" ? 'selected' : '' ?>>Cinzel (Regal & Classical)</option>
                            <option value="'Cormorant Garamond', serif" <?= ($settings['title_font'] ?? '') === "'Cormorant Garamond', serif" ? 'selected' : '' ?>>Cormorant Garamond (Graceful & Refined)</option>
                            <option value="'Lora', serif" <?= ($settings['title_font'] ?? '') === "'Lora', serif" ? 'selected' : '' ?>>Lora (Elegant & Modern)</option>
                            <option value="'Merriweather', serif" <?= ($settings['title_font'] ?? '') === "'Merriweather', serif" ? 'selected' : '' ?>>Merriweather (Sophisticated)</option>
                            <option value="'EB Garamond', serif" <?= ($settings['title_font'] ?? '') === "'EB Garamond', serif" ? 'selected' : '' ?>>EB Garamond (Timeless & Classic)</option>
                        </optgroup>
                        <optgroup label="Classic & Traditional">
                            <option value="'Times New Roman', serif" <?= ($settings['title_font'] ?? '') === "'Times New Roman', serif" ? 'selected' : '' ?>>Times New Roman (Classic)</option>
                            <option value="Georgia, serif" <?= ($settings['title_font'] ?? '') === 'Georgia, serif' ? 'selected' : '' ?>>Georgia (Formal)</option>
                            <option value="'Palatino Linotype', serif" <?= ($settings['title_font'] ?? '') === "'Palatino Linotype', serif" ? 'selected' : '' ?>>Palatino (Refined)</option>
                            <option value="'Baskerville', serif" <?= ($settings['title_font'] ?? '') === "'Baskerville', serif" ? 'selected' : '' ?>>Baskerville (Distinguished)</option>
                        </optgroup>
                        <optgroup label="Modern & Bold">
                            <option value="'Montserrat', sans-serif" <?= ($settings['title_font'] ?? '') === "'Montserrat', sans-serif" ? 'selected' : '' ?>>Montserrat (Bold & Contemporary)</option>
                            <option value="'Raleway', sans-serif" <?= ($settings['title_font'] ?? '') === "'Raleway', sans-serif" ? 'selected' : '' ?>>Raleway (Elegant & Thin)</option>
                            <option value="Arial, sans-serif" <?= ($settings['title_font'] ?? '') === 'Arial, sans-serif' ? 'selected' : '' ?>>Arial (Clean & Modern)</option>
                            <option value="Verdana, sans-serif" <?= ($settings['title_font'] ?? '') === 'Verdana, sans-serif' ? 'selected' : '' ?>>Verdana (Bold & Clear)</option>
                            <option value="Impact, sans-serif" <?= ($settings['title_font'] ?? '') === 'Impact, sans-serif' ? 'selected' : '' ?>>Impact (Strong & Powerful)</option>
                        </optgroup>
                    </select>
                    <small class="text-muted">Choose the font style for the pageant title display</small>
                    
                    <!-- Font Preview -->
                    <div class="mt-2 p-3 bg-light rounded">
                        <p class="mb-1 small text-muted">Preview:</p>
                        <h4 id="fontPreview" style="font-family: <?= esc($settings['title_font'] ?? 'Arial, sans-serif') ?>; margin: 0;"><?= esc($settings['system_name'] ?? 'PAGEANT TITLE') ?></h4>
                    </div>
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
            
            <!-- FORM 2: Theme Settings (Colors & Background) -->
            <form action="<?= base_url('admin/settings/update-theme') ?>" method="post" enctype="multipart/form-data" id="themeForm">
                <?= csrf_field() ?>
                
                <!-- Hidden field for selected preset -->
                <input type="hidden" name="theme_preset" id="theme_preset" value="<?= esc($settings['theme_preset'] ?? 'custom') ?>">
                
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

                    <!-- Button Color -->
                    <div class="color-picker-group">
                        <input 
                            type="color" 
                            id="button_color" 
                            name="button_color" 
                            value="<?= esc($settings['button_color'] ?? ($settings['primary_color'] ?? '#667eea')) ?>"
                        >
                        <div>
                            <strong>Button Color</strong>
                            <p class="small text-muted mb-0">Color for primary buttons</p>
                        </div>
                    </div>
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
    const buttonColor = document.getElementById('button_color');
    
    // Get preview elements
    const previewBox = document.getElementById('previewBox');
    const previewBtn = document.getElementById('previewBtn');
    const previewText = document.getElementById('previewText');
    const previewDescription = document.getElementById('previewDescription');
    const presetField = document.getElementById('theme_preset');
    
    if (presetField && presetField.value !== 'custom') {
        presetField.value = 'custom';
    }

    /**
     * Update preview colors
     * This function applies the selected colors to the preview box
     */
    function updatePreview() {
        const primary = primaryColor.value;
        const accent = accentColor.value;
        const text = textColor.value;
        const button = (buttonColor?.value || primary);

        // Gradient preview background based on colors
        previewBox.style.background = `linear-gradient(135deg, ${primary} 0%, ${accent} 100%)`;

        // Update button colors
        previewBtn.style.background = button;
        previewBtn.style.borderColor = button;
        previewBtn.style.color = 'white';

        // Text colors
        previewText.style.color = text;
        previewDescription.style.color = text;
    }
    
    /**
     * Show/hide background options based on type
     */
    // no background type toggle needed
    
    // Event Listeners (when user interacts with inputs)
    function handleColorChange() {
        if (presetField) {
            presetField.value = 'custom';
        }
        updatePreview();
    }

    primaryColor.addEventListener('input', handleColorChange);
    accentColor.addEventListener('input', handleColorChange);
    textColor.addEventListener('input', handleColorChange);
    if (buttonColor) buttonColor.addEventListener('input', handleColorChange);
    
    // Initialize on page load
    updatePreview();
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
