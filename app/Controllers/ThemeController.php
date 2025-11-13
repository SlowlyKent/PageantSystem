<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingsModel;

/**
 * Theme Controller
 * Generates dynamic CSS based on theme settings
 */
class ThemeController extends BaseController
{
    /**
     * Generate dynamic CSS file based on current theme settings
     * This is accessed as /theme.css
     */
    public function css()
    {
        $settingsModel = new SettingsModel();
        
        // Get theme colors from database
        $primaryColor = $settingsModel->getSetting('primary_color') ?: '#667eea';
        $accentColor  = $settingsModel->getSetting('accent_color') ?: '#764ba2';
        $textColor    = $settingsModel->getSetting('text_color') ?: '#333333';
        $buttonColor  = $settingsModel->getSetting('button_color') ?: $primaryColor;
        
        // Calculate lighter and darker shades
        $primaryLight = $this->adjustBrightness($primaryColor, 20);
        $primaryDark  = $this->adjustBrightness($primaryColor, -20);
        $accentLight  = $this->adjustBrightness($accentColor, 20);
        $accentDark   = $this->adjustBrightness($accentColor, -20);
        $buttonLight  = $this->adjustBrightness($buttonColor, 20);
        $buttonDark   = $this->adjustBrightness($buttonColor, -20);

        // Determine contrast text colors
        $primaryContrast = $this->calculateContrastColor($primaryColor);
        $accentContrast  = $this->calculateContrastColor($accentColor);
        $buttonContrast  = $this->calculateContrastColor($buttonColor);
        
        // Background: make the app background plain white (only sidebar is themed)
        $bgCss = "body { background: #ffffff !important; }";

        // Generate CSS
        $css = "
/* ========================================
   DYNAMIC THEME CSS - Auto-generated
   Primary Color: {$primaryColor}
   Accent Color: {$accentColor}
   Text Color: {$textColor}
   ======================================== */

{$bgCss}

/* BASE TEXT COLOR */
:root {
    --theme-text-color: {$textColor};
    --theme-primary-color: {$primaryColor};
    --theme-primary-rgb: {$this->hexToRgb($primaryColor)};
    --theme-primary-light: {$primaryLight};
    --theme-primary-dark: {$primaryDark};
    --theme-primary-contrast: {$primaryContrast};
    --theme-accent-color: {$accentColor};
    --theme-accent-rgb: {$this->hexToRgb($accentColor)};
    --theme-accent-light: {$accentLight};
    --theme-accent-dark: {$accentDark};
    --theme-accent-contrast: {$accentContrast};
    --theme-button-color: {$buttonColor};
    --theme-button-rgb: {$this->hexToRgb($buttonColor)};
    --theme-button-light: {$buttonLight};
    --theme-button-dark: {$buttonDark};
    --theme-button-contrast: {$buttonContrast};
    --theme-gradient-135: linear-gradient(135deg, {$primaryColor} 0%, {$accentColor} 100%);
    --theme-gradient-90: linear-gradient(90deg, {$primaryColor} 0%, {$accentColor} 100%);
}
body, main, .text-body { color: var(--theme-text-color) !important; }
.section-card p, .card p, .table, .form-label, .nav-link { color: var(--theme-text-color) !important; }
.text-theme { color: var(--theme-text-color) !important; }

/* KEEP BLACK TEXT FOR VISIBILITY */
.stats-content h3,
.stats-content p,
.section-card h5,
.section-card h4,
.section-card strong,
.list-group-item strong,
.contestant-name {
    color: #333 !important;
}

.badge.bg-primary,
.badge.bg-warning,
.badge.bg-secondary,
.badge.bg-success {
    color: #fff !important;
}

/* PRIMARY BUTTONS (configurable) */
.btn-primary {
    background: var(--theme-button-color) !important;
    border-color: var(--theme-button-color) !important;
    color: var(--theme-button-contrast, #ffffff) !important;
}

.btn-primary:hover,
.btn-primary:focus {
    background: {$buttonDark} !important;
    border-color: {$buttonDark} !important;
    box-shadow: 0 4px 12px rgba({$this->hexToRgb($buttonColor)}, 0.4) !important;
}

.btn-primary:active {
    background: {$buttonDark} !important;
    border-color: {$buttonDark} !important;
}

/* SECONDARY/ACCENT BUTTONS */
.btn-info {
    background: {$accentColor} !important;
    border-color: {$accentColor} !important;
    color: {$accentContrast} !important;
}

.btn-info:hover,
.btn-info:focus {
    background: {$accentDark} !important;
    border-color: {$accentDark} !important;
}

/* THEME OUTLINE SECONDARY (e.g., Back to List) */
.btn-secondary,
.btn-outline-secondary {
    background: transparent !important;
    color: var(--theme-button-color) !important;
    border-color: var(--theme-button-color) !important;
}

.btn-secondary:hover, .btn-secondary:focus,
.btn-outline-secondary:hover, .btn-outline-secondary:focus {
    background: {$buttonColor} !important;
    color: var(--theme-button-contrast, #ffffff) !important;
    border-color: {$buttonColor} !important;
}

/* OUTLINE BUTTONS WITH PRIMARY COLOR */
.btn-outline-primary {
    color: {$primaryColor} !important;
    border-color: {$primaryColor} !important;
}

.btn-outline-primary:hover {
    background: {$primaryColor} !important;
    border-color: {$primaryColor} !important;
    color: #ffffff !important;
}

/* TABLE HEADERS */
.table thead th,
.table-light th,
.card-header {
    background: linear-gradient(135deg, {$primaryLight} 0%, {$primaryColor} 100%) !important;
    color: {$primaryContrast} !important;
    border-color: {$primaryDark} !important;
}

.table th {
    background: linear-gradient(135deg, {$primaryLight} 0%, {$primaryColor} 100%) !important;
    color: {$primaryContrast} !important;
}

/* ALTERNATIVE TABLE HEADER STYLES */
.bg-primary {
    background: {$primaryColor} !important;
}

.bg-info {
    background: {$accentColor} !important;
}

/* BADGES WITH PRIMARY COLOR */
.badge.bg-primary {
    background: {$primaryColor} !important;
    color: {$primaryContrast} !important;
}

.badge.bg-info {
    background: {$accentColor} !important;
    color: {$accentContrast} !important;
}

/* CARD HEADERS */
.card-header.bg-primary {
    background: {$primaryColor} !important;
    color: {$primaryContrast} !important;
}

.card-header.bg-info {
    background: {$accentColor} !important;
    color: {$accentContrast} !important;
}

/* SIDEBAR - Main Background */
.sidebar {
    background: linear-gradient(180deg, {$primaryColor} 0%, {$accentColor} 30%, {$primaryColor} 60%, {$accentColor} 100%) !important;
}

/* SIDEBAR HEADER - Admin Panel Section */
.sidebar-header {
    background: linear-gradient(135deg, {$primaryColor} 0%, {$accentColor} 100%) !important;
    color: #ffffff !important;
}

.sidebar-header h4,
.sidebar-header p {
    color: #ffffff !important;
}

/* SIDEBAR TITLE (for Judge Menu, etc.) */
.sidebar-title {
    background: linear-gradient(135deg, {$primaryColor} 0%, {$accentColor} 100%) !important;
    color: #ffffff !important;
    padding: 20px;
    text-align: center;
    font-weight: bold;
    font-size: 1.2rem;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

/* SIDEBAR NAVIGATION LINKS */
.sidebar .nav-link {
    color: rgba(255, 255, 255, 0.85) !important;
}

.sidebar .nav-link:hover {
    background: rgba(255, 255, 255, 0.15) !important;
    color: #ffffff !important;
    border-left: 4px solid #ffffff !important;
}

.sidebar .nav-link.active {
    background: rgba(255, 255, 255, 0.2) !important;
    color: #ffffff !important;
    border-left: 4px solid #ffffff !important;
}

/* SIDEBAR ICON COLORS */
.sidebar .nav-link i {
    color: rgba(255, 255, 255, 0.9) !important;
}

.sidebar .nav-link:hover i,
.sidebar .nav-link.active i {
    color: #ffffff !important;
}

/* NAVIGATION PILLS/TABS */
.nav-pills .nav-link.active {
    background: {$primaryColor} !important;
}

/* FORM CONTROLS FOCUS */
.form-control:focus,
.form-select:focus {
    border-color: {$primaryColor} !important;
    box-shadow: 0 0 0 0.25rem rgba({$this->hexToRgb($primaryColor)}, 0.25) !important;
}

/* PAGINATION */
.pagination .page-link {
    color: {$primaryColor} !important;
}

.pagination .page-item.active .page-link {
    background: {$primaryColor} !important;
    border-color: {$primaryColor} !important;
}

/* ALERTS */
.alert-primary {
    background: {$primaryLight} !important;
    border-color: {$primaryColor} !important;
    color: {$primaryDark} !important;
}

/* PROGRESS BARS */
.progress-bar {
    background: {$primaryColor} !important;
}

/* LINKS */
a {
    color: {$primaryColor} !important;
}

a:hover {
    color: {$primaryDark} !important;
}

/* FORM CHECK (Checkboxes/Radio) */
.form-check-input:checked {
    background-color: {$primaryColor} !important;
    border-color: {$primaryColor} !important;
}

/* DROPDOWN MENU ACTIVE */
.dropdown-item:active,
.dropdown-item.active {
    background: {$primaryColor} !important;
}

/* TEXT PRIMARY COLOR */
.text-primary {
    color: {$primaryColor} !important;
}

/* BORDER PRIMARY COLOR */
.border-primary {
    border-color: {$primaryColor} !important;
}

/* STATS CARDS (if you have them) */
.stats-icon {
    background: linear-gradient(135deg, {$primaryColor} 0%, {$accentColor} 100%) !important;
}

/* CUSTOM BUTTONS */
.add-criteria-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

/* SEGMENT HEADERS */
.segment-header-1 {
    background: linear-gradient(135deg, {$primaryColor} 0%, {$accentColor} 100%) !important;
}

/* SECTION CARDS (apply theme to headings and dividers) */
.section-card > h5 { color: {$primaryColor} !important; }
.section-card > hr { border-top: 2px solid {$primaryColor} !important; opacity: 1 !important; }

/* RESULTS PAGE - Overall card (uses theme gradient) */
.overall-card {
    background: linear-gradient(135deg, {$primaryColor} 0%, {$accentColor} 100%) !important;
    color: #ffffff !important;
}

/* RESULTS PAGE - Round cards left border uses primary */
.result-card {
    border-left-color: {$primaryColor} !important;
}

/* Hover shadow tint */
.result-card:hover {
    box-shadow: 0 10px 24px rgba({$this->hexToRgb($primaryColor)}, 0.25) !important;
}

/* ROUND CARDS */
.round-card {
    border-left: 4px solid {$primaryColor} !important;
}

/* HOVER EFFECTS */
.card:hover {
    border-color: {$primaryColor} !important;
}

/* ACTIVE NAVIGATION */
.nav-link.active {
    background: {$primaryColor} !important;
    color: #ffffff !important;
}

/* MODAL HEADERS */
.modal-header {
    background: {$primaryColor} !important;
    color: #ffffff !important;
}

/* SPINNER/LOADER */
.spinner-border.text-primary {
    color: {$primaryColor} !important;
}

/* LIST GROUP ACTIVE */
.list-group-item.active {
    background: {$primaryColor} !important;
    border-color: {$primaryColor} !important;
}
";

        // Set response headers for CSS
        return $this->response
            ->setContentType('text/css')
            ->setBody($css)
            ->setHeader('Cache-Control', 'no-cache, must-revalidate')
            ->setHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
    }

    /**
     * Adjust brightness of a hex color
     * @param string $hex Hex color code
     * @param int $steps Positive to lighten, negative to darken
     * @return string Adjusted hex color
     */
    private function adjustBrightness($hex, $steps)
    {
        // Remove # if present
        $hex = str_replace('#', '', $hex);
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Adjust
        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));
        
        // Convert back to hex
        return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT)
                   . str_pad(dechex($g), 2, '0', STR_PAD_LEFT)
                   . str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Convert hex color to RGB string for rgba()
     * @param string $hex Hex color code
     * @return string RGB values (e.g., "102, 126, 234")
     */
    private function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return "$r, $g, $b";
    }

    /**
     * Determine contrasting text color (black/white) for a given background color
     */
    private function calculateContrastColor($hex)
    {
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return ($brightness > 160) ? '#111111' : '#ffffff';
    }
}
