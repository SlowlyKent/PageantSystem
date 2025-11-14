<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>System Settings<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Google Fonts for Font Preview -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Cinzel:wght@400;700;900&family=Cormorant+Garamond:wght@400;700&family=Lora:wght@400;700&family=Merriweather:wght@400;700;900&family=EB+Garamond:wght@400;700&family=Montserrat:wght@400;700;900&family=Raleway:wght@400;700&display=swap" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$themePresets = [
    [
        'id'      => 'golden_hour',
        'label'   => 'Golden Hour',
        'primary' => '#3A2B20',
        'accent'  => '#F5C26B',
        'text'    => '#FDF6EA',
        'button'  => '#D4933F',
    ],
    [
        'id'      => 'imperial_dusk',
        'label'   => 'Imperial Dusk',
        'primary' => '#1F2035',
        'accent'  => '#E8B980',
        'text'    => '#F9F6F1',
        'button'  => '#DA9F5B',
    ],
    [
        'id'      => 'champagne_noir',
        'label'   => 'Champagne Noir',
        'primary' => '#2B2C34',
        'accent'  => '#F4D9B2',
        'text'    => '#FEFAF3',
        'button'  => '#D8B171',
    ],
    [
        'id'      => 'sunrise_velvet',
        'label'   => 'Sunrise Velvet',
        'primary' => '#4B244A',
        'accent'  => '#F6B756',
        'text'    => '#FDEFE0',
        'button'  => '#E89C42',
    ],
    [
        'id'      => 'opulent_jade',
        'label'   => 'Opulent Jade',
        'primary' => '#123C3B',
        'accent'  => '#E3B04B',
        'text'    => '#F5F1E3',
        'button'  => '#C38E2F',
    ],
    [
        'id'      => 'royal_navy',
        'label'   => 'Royal Navy & Gold',
        'primary' => '#101E3C',
        'accent'  => '#D4AF37',
        'text'    => '#F8F4EA',
        'button'  => '#C4932A',
    ],
    [
        'id'      => 'desert_bloom',
        'label'   => 'Desert Bloom',
        'primary' => '#4F2F1B',
        'accent'  => '#F0C27B',
        'text'    => '#FFF7E8',
        'button'  => '#D7A45C',
    ],
    [
        'id'      => 'mocha_luxe',
        'label'   => 'Mocha Luxe',
        'primary' => '#2F2118',
        'accent'  => '#E9B87C',
        'text'    => '#F8F2EB',
        'button'  => '#C98E51',
    ],
    [
        'id'      => 'orchid_gold',
        'label'   => 'Orchid Gold',
        'primary' => '#4D1D3F',
        'accent'  => '#F6C26B',
        'text'    => '#FCEFEB',
        'button'  => '#E0A356',
    ],
    [
        'id'      => 'midnight_ember',
        'label'   => 'Midnight Ember',
        'primary' => '#1B1A2A',
        'accent'  => '#F5B867',
        'text'    => '#F6F3EC',
        'button'  => '#D68F45',
    ],
];
?>

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
                        <h4 id="fontPreview" class="font-preview title-font"><?= esc($settings['system_name'] ?? 'PAGEANT TITLE') ?></h4>
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
                <script>
                    window.themePresets = <?= json_encode($themePresets) ?>;
                </script>
                
                <!-- Theme Presets -->
                <div class="mb-4">
                    <label class="form-label d-flex align-items-center gap-2">
                        <i class="bi bi-lightning-charge-fill"></i>
                        Theme Presets
                    </label>
                    <select class="form-select" id="themePresetSelect">
                        <option value="custom">Custom (Current)</option>
                        <?php foreach ($themePresets as $preset): ?>
                            <option value="<?= esc($preset['id']) ?>" <?= ($settings['theme_preset'] ?? 'custom') === $preset['id'] ? 'selected' : '' ?>>
                                <?= esc($preset['label']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Choose a preset to instantly update the entire app.</small>
                </div>

                <!-- Color Pickers -->
                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-droplet-fill"></i> Admin Theme Colors</label>
                    
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
        <div class="settings-card position-sticky sticky-offset">
            <div class="settings-header">
                <h5><i class="bi bi-eye-fill"></i> Login Preview</h5>
            </div>
            
            <!-- Preview Box (changes colors in real-time) -->
            <div class="theme-preview-box" id="previewBox">
                <h3 id="previewText">Preview Theme</h3>
                <button class="preview-button" id="previewBtn">Sample Button</button>
                <p id="previewDescription">This is how the login page will look</p>
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
    const titleFontSelect = document.getElementById('title_font');
    
    // Get preview elements
    const previewBox = document.getElementById('previewBox');
    const previewBtn = document.getElementById('previewBtn');
    const previewText = document.getElementById('previewText');
    const previewDescription = document.getElementById('previewDescription');
    const presetField = document.getElementById('theme_preset');
    const presetSelect = document.getElementById('themePresetSelect');
    const presetData = Array.isArray(window.themePresets) ? window.themePresets : [];
    
    /**
     * Update preview colors
     * This function applies the selected colors to the preview box
     */
    function updatePreview() {
        const primary = primaryColor.value;
        const accent = accentColor.value;
        const text = textColor.value;
        const button = buttonColor?.value || primary;

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
        if (presetSelect) {
            presetSelect.value = 'custom';
        }
        updatePreview();
    }

    [primaryColor, accentColor, textColor, buttonColor].forEach(function(input) {
        if (input) {
            input.addEventListener('input', handleColorChange);
        }
    });
    
    function updateTitleFont(fontValue) {
        const fontChoice = fontValue || 'Arial, sans-serif';
        document.documentElement.style.setProperty('--title-font', fontChoice);
    }

    if (titleFontSelect) {
        updateTitleFont(titleFontSelect.value);
        titleFontSelect.addEventListener('change', function() {
            updateTitleFont(this.value);
        });
    }

    function applyPreset(id) {
        if (!id || id === 'custom') {
            presetField.value = 'custom';
            if (presetSelect) {
                presetSelect.value = 'custom';
            }
            updatePreview();
            return;
        }
        const preset = presetData.find(p => p.id === id);
        if (!preset) {
            return;
        }

        primaryColor.value = preset.primary;
        accentColor.value = preset.accent;
        textColor.value = preset.text;
        if (buttonColor) {
            buttonColor.value = preset.button;
        }
        presetField.value = preset.id;
        if (presetSelect) {
            presetSelect.value = preset.id;
        }
        updatePreview();
    }

    if (presetSelect) {
        presetSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                presetField.value = 'custom';
                updatePreview();
            } else {
                applyPreset(this.value);
            }
        });

        if (presetField) {
            presetSelect.value = presetField.value || 'custom';
        }
    }

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
