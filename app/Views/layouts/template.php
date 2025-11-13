<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - <?= esc(system_name()) ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom Dashboard CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    
    <!-- Page-Specific Styles (Consolidated from all views) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/pages.css') ?>">
    
    <!-- Dynamic Theme CSS (Auto-applies user-selected colors) -->
    <link rel="stylesheet" href="<?= base_url('theme.css') ?>?v=<?= time() ?>">
    
    <!-- Global overrides -->
    <style>
      /* Dashboard stats cards: white background, black text */
      .stats-card { background: #ffffff !important; color: var(--theme-text-color) !important; }
      .stats-card .stats-content h3,
      .stats-card .stats-content p { color: var(--theme-text-color) !important; }


      /* Icon circle for stats - Theme Aware */
      .icon-circle { 
        width: 56px; 
        height: 56px; 
        border-radius: 14px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        background: var(--theme-gradient-135, linear-gradient(135deg, #667eea, #764ba2)) !important; 
        color: #ffffff !important; 
        border: none;
        box-shadow: 0 4px 12px rgba(var(--theme-primary-rgb, 102, 126, 234), 0.25);
        transition: all 0.3s ease;
        font-size: 1.4rem;
      }
      .icon-circle:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(var(--theme-primary-rgb, 102, 126, 234), 0.35);
      }
      
      /* Enhanced Stat Box - Theme Aware */
      .stat-box {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
      }
      .stat-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--theme-gradient-90, linear-gradient(90deg, #667eea, #764ba2));
      }
      .stat-box:hover { 
        box-shadow: 0 8px 20px rgba(var(--theme-primary-rgb, 102, 126, 234), 0.18); 
        transform: translateY(-4px);
        border-color: rgba(var(--theme-primary-rgb, 102, 126, 234), 0.2);
      }
      .stat-box .value { 
        font-size: 1.8rem; 
        font-weight: 700; 
        color: var(--theme-text-color, #212529); 
        line-height: 1.2; 
        margin-top: 4px;
      }
      .stat-box .label { 
        color: #6c757d; 
        font-size: 0.9rem; 
        margin-top: 4px; 
        font-weight: 500;
      }
      
      /* Enhanced Section Cards - Theme Aware */
      .section-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s ease;
      }
      .section-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
      }
      .section-card .card-header.bg-primary {
        background: var(--theme-gradient-135, linear-gradient(135deg, #667eea, #764ba2)) !important;
        border: none;
        padding: 16px 20px;
      }
      .section-card .card-header h5 {
        font-weight: 600;
        font-size: 1.1rem;
        color: #ffffff !important;
      }
      
      /* Progress Bar - Theme Aware */
      .progress-bar {
        background: var(--theme-gradient-90, linear-gradient(90deg, #667eea, #764ba2)) !important;
      }
      
      /* Page Header - Theme Aware */
      .page-header h1 {
        background: var(--theme-gradient-135, linear-gradient(135deg, #667eea, #764ba2));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        font-size: 2rem;
      }
      
      /* Judge Item Hover */
      .judge-item {
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s;
        border-radius: 8px;
        margin: 4px 0;
      }
      .judge-item:hover {
        background: #f8f9fa;
        padding-left: 12px;
        padding-right: 12px;
      }
      .judge-item:last-child {
        border-bottom: none;
      }
      
      /* Status Badges - Theme Aware */
      .round-status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
      }
      .round-status-active {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
      }
      .round-status-completed {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(34, 197, 94, 0.25);
      }
      .round-status-pending {
        background: #f3f4f6;
        color: #6b7280;
      }

      /* Wireframe-style empty panel area */
      .dashboard-panel { height: 300px; background: #e9ecef; border: 1px solid #dfe3e6; border-radius: 10px; }

      /* Section headings */
      .section-card h5 {
        font-weight: 600;
        color: #1f1f1f !important;
      }
      .section-card hr { margin: .5rem 0 1rem; color: #e5e7eb; opacity: 1; }

      /* Judge Dashboard - Quick Actions */
      .quick-actions-box {
        position: relative;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: #ffffff;
        padding: 36px 24px 24px;
      }

      .quick-actions-header {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%);
        padding: 0 16px;
        background: #ffffff;
        font-weight: 600;
        color: #1f1f1f;
      }

      .quick-actions-buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px;
      }

      .quick-action-btn {
        flex: 1 1 45%;
        padding: 18px 32px;
        border-radius: 6px;
        border: 1px solid var(--theme-primary-color, #667eea);
        background: linear-gradient(
          135deg,
          rgba(var(--theme-primary-rgb, 102, 126, 234), 0.12),
          rgba(var(--theme-accent-rgb, 118, 75, 162), 0.12)
        );
        color: var(--theme-primary-color, #4c51bf) !important;
        text-decoration: none;
        font-weight: 600;
        text-align: center;
        transition: background 0.2s ease, transform 0.2s ease;
      }

      .quick-action-btn.primary {
        background: var(--theme-gradient-135, linear-gradient(135deg, #667eea, #764ba2));
        color: var(--theme-primary-contrast, #ffffff) !important;
        border-color: var(--theme-primary-dark, #4c51bf);
      }

      .quick-action-btn:hover {
        background: linear-gradient(
          135deg,
          rgba(var(--theme-primary-rgb, 102, 126, 234), 0.18),
          rgba(var(--theme-accent-rgb, 118, 75, 162), 0.18)
        );
        transform: translateY(-1px);
      }

      .quick-action-btn.primary:hover {
        background: linear-gradient(
          135deg,
          var(--theme-primary-dark, #4c51bf),
          var(--theme-accent-dark, #5a347f)
        );
        border-color: var(--theme-primary-dark, #4c51bf);
        color: var(--theme-primary-contrast, #ffffff) !important;
      }

      /* Preserve admin action button brand colors */
      .action-btn.btn-info {
        background: #0dcaf0 !important;
        border-color: #0dcaf0 !important;
        color: #ffffff !important;
      }

      .action-btn.btn-warning {
        background: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #212529 !important;
      }

      .action-btn.btn-danger {
        background: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #ffffff !important;
      }

      /* Theme-aware Tables */
      .table thead th {
        background: linear-gradient(135deg, var(--theme-primary-light, #8794f7), var(--theme-primary-color, #667eea)) !important;
        color: var(--theme-primary-contrast, #ffffff) !important;
        border-bottom: 2px solid var(--theme-primary-dark, #4c51bf) !important;
      }

      .table thead tr {
        border-color: var(--theme-primary-dark, #4c51bf) !important;
      }

      .table-striped tbody tr:nth-of-type(odd) {
        background: rgba(var(--theme-primary-rgb, 102, 126, 234), 0.05);
      }

      .table tbody tr:hover td {
        background: rgba(var(--theme-primary-rgb, 102, 126, 234), 0.08);
      }

      /* Theme-aware badges */
      .badge.bg-primary,
      .badge-primary,
      .badge.theme-primary {
        background: var(--theme-primary-color, #667eea) !important;
        color: var(--theme-primary-contrast, #ffffff) !important;
      }

      .badge.bg-accent,
      .badge-accent,
      .badge.theme-accent {
        background: var(--theme-accent-color, #764ba2) !important;
        color: var(--theme-accent-contrast, #ffffff) !important;
      }

      /* Theme-aware form controls */
      .form-check-input:checked {
        background-color: var(--theme-primary-color, #667eea);
        border-color: var(--theme-primary-color, #667eea);
      }

      .form-switch .form-check-input:checked {
        background-color: var(--theme-primary-color, #667eea);
      }

      /* Theme-aware links */
      a.theme-link,
      .theme-link {
        color: var(--theme-button-color, #667eea);
        font-weight: 600;
      }

      a.theme-link:hover,
      .theme-link:hover {
        color: var(--theme-button-dark, #4c51bf);
      }

      /* Enhanced dashboard panel styles */
      .dashboard-panel {
        background: #f7f7f7;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.06);
      }
      .dashboard-panel .card-body { padding: 1.5rem; }
      .dashboard-panel .card-title { font-weight: 600; color: #333; }
      .dashboard-panel .card-text { color: #666; }

      /* Highlighted summary statistics (used on list pages) */
      .summary-stats { border: 1px solid #e6e6e6; border-radius: 14px; box-shadow: 0 8px 22px rgba(0,0,0,0.06); }
      .summary-stats .row.text-center { align-items: center; }
      .summary-stats .row.text-center > [class^="col-"] { 
        padding: 18px 8px; 
        position: relative; 
        transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
      }
      .summary-stats .row.text-center > [class^="col-"]::after { 
        content: ""; position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px; background: #eef1f4; 
      }
      .summary-stats .row.text-center > [class^="col-"]:last-child::after { display: none; }
      .summary-stats h3 { font-size: 1.8rem; font-weight: 800; margin: 0; }
      .summary-stats p { margin: 4px 0 0; font-size: .92rem; color: #6c757d; }
      .summary-stats .row.text-center > [class^="col-"]:hover { 
        background: #f9fafb; transform: translateY(-2px); box-shadow: inset 0 1px 0 rgba(0,0,0,0.02);
        border-radius: 10px; 
      }
      
      /* Scoring Page Styles */
      body { background: #f8f9fa; }
      
      /* Theme variables for scoring */
      :root {
        --scoring-primary: var(--theme-primary-color, #667eea);
        --scoring-accent: var(--theme-accent-color, #764ba2);
      }
      
      /* Round Navigation (wireframe-style progress pills) */
      .rounds-navigation {
        background: #ffffff;
        padding: 18px 32px;
        border-radius: 999px;
        box-shadow: 0 8px 24px rgba(var(--theme-primary-rgb, 102, 126, 234), 0.12);
        margin-bottom: 32px;
        display: flex;
        justify-content: center;
        align-items: center;
      }
      
      .round-progress-track {
        display: flex;
        align-items: center;
        gap: 18px;
      }
      
      .round-step {
        width: 60px;
        height: 60px;
        border-radius: 999px;
        background: #f1f4f9;
        color: #94a3b8;
        font-weight: 700;
        font-size: 20px;
        border: 4px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(148, 163, 184, 0.15);
        transition: all 0.3s ease;
        cursor: pointer;
      }
      
      .round-step.active {
        background: var(--theme-gradient-135, linear-gradient(135deg, #667eea, #764ba2));
        color: #ffffff;
        border-color: rgba(255,255,255,0.25);
        box-shadow: 0 10px 26px rgba(var(--theme-primary-rgb, 102, 126, 234), 0.25);
      }
      
      .round-step.completed {
        background: rgba(var(--theme-primary-rgb, 102, 126, 234), 0.15);
        color: var(--theme-primary-color, #667eea);
        border-color: rgba(var(--theme-primary-rgb, 102, 126, 234), 0.25);
      }
      
      .round-step.locked {
        background: #f4f4f5;
        color: rgba(var(--theme-primary-rgb, 102, 126, 234), 0.45);
        border-color: transparent;
        cursor: not-allowed;
        opacity: 0.6;
      }
      
      .round-connector {
        width: 56px;
        height: 4px;
        border-radius: 999px;
        background: #e2e8f0;
        transition: background 0.3s ease;
      }
      
      .round-connector.completed {
        background: var(--theme-gradient-90, linear-gradient(90deg, #667eea, #764ba2));
      }
      
      /* Round Header Card (wireframe-style) */
      .round-info-card {
        background: #ffffff;
        border-radius: 28px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        position: relative;
        overflow: hidden;
      }
      
      .round-info-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(var(--theme-primary-rgb, 102, 126, 234),0.12), rgba(var(--theme-accent-rgb, 118, 75, 162),0.08));
        opacity: 0.6;
        pointer-events: none;
      }
      
      .round-info-card > * {
        position: relative;
        z-index: 1;
      }
      
      .round-icon {
        width: 96px;
        height: 96px;
        border-radius: 999px;
        background: var(--theme-gradient-135, linear-gradient(135deg, #667eea, #764ba2));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 34px;
        font-weight: 700;
        box-shadow: 0 12px 24px rgba(var(--theme-primary-rgb, 102, 126, 234), 0.25);
        border: 4px solid rgba(255,255,255,0.65);
        margin-bottom: 8px;
      }
      
      .round-title {
        font-size: 1.9rem;
        font-weight: 700;
        color: var(--theme-text-color, #111827);
        margin-bottom: 4px;
        letter-spacing: -0.01em;
        text-align: center;
      }
      
      .round-subtitle {
        color: #6b7280;
        font-size: 0.98rem;
        margin: 0;
        text-align: center;
      }
      
      .round-info-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 18px;
        justify-content: center;
      }
      
      .round-info-stat {
        min-width: 180px;
        background: #ffffff;
        border-radius: 18px;
        padding: 14px 20px;
        box-shadow: 0 10px 25px rgba(var(--theme-primary-rgb, 102, 126, 234), 0.16);
        border: 1px solid rgba(var(--theme-primary-rgb, 102, 126, 234), 0.15);
      }
      
      .round-info-stat .stat-value {
        display: block;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--theme-primary-color, #667eea);
        margin-bottom: 4px;
      }
      
      .round-info-stat .stat-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #6c7280;
      }
      
      /* Scoring Categories - formal layout */
      .categories-section {
        background: #ffffff;
        border-radius: 22px;
        padding: 26px 30px;
        margin-bottom: 32px;
        box-shadow: 0 12px 30px rgba(148, 163, 184, 0.16);
        border: 1px solid rgba(229, 231, 235, 0.9);
      }
      
      .categories-section h5 {
        color: var(--theme-primary-color, #667eea) !important;
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 18px;
      }
      
      .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 18px;
      }
      
      .category-badge {
        background: linear-gradient(135deg, rgba(var(--theme-primary-rgb, 102, 126, 234), 0.12) 0%, rgba(var(--theme-accent-rgb, 118, 75, 162), 0.08) 100%);
        border-radius: 16px;
        padding: 16px 18px;
        border: 1px solid rgba(var(--theme-primary-rgb, 102, 126, 234), 0.2);
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(var(--theme-primary-rgb, 102, 126, 234), 0.12);
      }
      
      .category-badge:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(var(--theme-primary-rgb, 102, 126, 234), 0.18);
      }
      
      .category-name {
        font-weight: 700;
        color: #1f2937;
        display: block;
        margin-bottom: 6px;
        font-size: 15px;
      }
      
      .category-desc {
        font-size: 12px;
        color: #6b7280;
        display: block;
        margin-bottom: 8px;
        line-height: 1.4;
      }
      
      .category-points {
        color: var(--theme-primary-color, #667eea);
        font-weight: 600;
        font-size: 13px;
        background: rgba(var(--theme-primary-rgb, 102, 126, 234), 0.15);
        padding: 4px 12px;
        border-radius: 18px;
        display: inline-block;
      }
      
      /* Contestant Scoring Cards */
      .contestant-scoring-card {
        background: white;
        border-radius: 20px;
        padding: 35px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 2px solid #f0f0f0;
        transition: all 0.3s;
      }
      
      .contestant-scoring-card:hover { 
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        border-color: var(--scoring-primary);
        transform: translateY(-2px);
      }
      
      .contestant-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--scoring-primary);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      }
      
      .contestant-name {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #333;
      }
      
      .contestant-meta {
        color: #6c757d;
        font-size: 14px;
        background: #f8f9fa;
        padding: 4px 12px;
        border-radius: 15px;
        display: inline-block;
        margin: 3px 0;
      }
      
      /* Slider Styling - Enhanced Elegant Design */
      .criteria-slider-group { 
        margin-bottom: 0;
        padding: 18px;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
      }
      
      .criteria-slider-group:hover {
        background: #ffffff;
        border-color: var(--scoring-primary, #667eea);
        box-shadow: 0 4px 12px rgba(var(--theme-primary-rgb, 102, 126, 234), 0.15);
        transform: translateY(-2px);
      }
      
      /* Contestant Scoring Cards - Enhanced Elegant Design */
      .contestant-scoring-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 16px 40px rgba(148, 163, 184, 0.18);
        border: 1px solid rgba(229, 231, 235, 0.9);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }
      
      .contestant-scoring-card:hover { 
        box-shadow: 0 20px 48px rgba(148, 163, 184, 0.24);
        transform: translateY(-4px);
      }
      
      .contestant-header {
        display: flex;
        gap: 20px;
        align-items: center;
        margin-bottom: 24px;
      }
      
      .contestant-info h5 {
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 4px;
        color: #1f2937;
      }
      
      .contestant-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 0.9rem;
        color: #64748b;
      }
      
      .contestant-meta-row span {
        background: #f8fafc;
        padding: 4px 12px;
        border-radius: 14px;
        border: 1px solid rgba(148, 163, 184, 0.2);
      }
      
      /* Segment Scoring Section */
      .segment-block {
        background: #f8faff;
        border-radius: 20px;
        border: 1px solid rgba(var(--theme-primary-rgb, 102, 126, 234), 0.18);
        margin-bottom: 26px;
        overflow: hidden;
        box-shadow: 0 12px 28px rgba(var(--theme-primary-rgb, 102, 126, 234), 0.12);
      }
      
      .segment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        background: var(--theme-gradient-135, linear-gradient(135deg, #667eea, #764ba2));
        color: #ffffff;
        padding: 18px 26px;
      }
      
      .segment-header-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
      }
      
      .segment-name {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.85);
      }
      
      .segment-title {
        font-weight: 600;
        font-size: 1.05rem;
      }
      
      .segment-weight {
        font-size: 0.9rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.18);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.2);
      }
      
      .segment-criteria-grid {
        padding: 22px 26px 26px;
        background: #ffffff;
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      }
      
      .criteria-card {
        background: #f8f9ff;
        border-radius: 18px;
        padding: 18px 20px;
        border: 1.5px solid rgba(var(--theme-primary-rgb, 102, 126, 234), 0.18);
        box-shadow: 0 10px 24px rgba(var(--theme-primary-rgb, 102, 126, 234), 0.12);
        display: flex;
        flex-direction: column;
        gap: 14px;
      }
      
      .criteria-title {
        font-weight: 600;
        color: #1f2937;
        font-size: 1rem;
      }
      
      .criteria-description {
        font-size: 0.85rem;
        color: #64748b;
      }
      
      .criteria-labels {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.85rem;
        color: #475569;
      }
      
      .criteria-slider-wrapper {
        display: flex;
        flex-direction: column;
        gap: 10px;
      }
      
      .score-display {
        align-self: flex-end;
        font-weight: 700;
        color: var(--scoring-primary, #667eea);
        font-size: 1rem;
        padding: 4px 12px;
        border-radius: 999px;
        background: rgba(var(--theme-primary-rgb, 102, 126, 234), 0.12);
      }
      
      .criteria-range-labels {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: #94a3b8;
      }
      
      .criteria-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
      }
      
      .criteria-name {
        font-weight: 700;
        color: #333;
        font-size: 16px;
      }
      
      .criteria-desc {
        font-size: 13px;
        color: #6c757d;
        margin-top: 4px;
      }
      
      .score-display {
        font-size: 24px;
        font-weight: 800;
        color: var(--scoring-primary);
        min-width: 90px;
        text-align: right;
        background: white;
        padding: 8px 16px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
      }
      
      .form-range { 
        height: 10px;
        border-radius: 10px;
        background: #e9ecef;
      }
      
      .form-range::-webkit-slider-thumb {
        width: 24px;
        height: 24px;
        background: var(--scoring-primary);
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        transition: all 0.2s;
      }
      
      .form-range::-webkit-slider-thumb:hover {
        transform: scale(1.2);
      }
      
      .form-range::-moz-range-thumb {
        width: 24px;
        height: 24px;
        background: var(--scoring-primary);
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        border: none;
      }
      
      /* Buttons */
      .complete-scoring-btn {
        background: var(--scoring-primary);
        border: none;
        padding: 15px 50px;
        font-size: 18px;
        font-weight: 600;
        border-radius: 10px;
        color: white;
        transition: all 0.3s ease;
      }
      
      .complete-scoring-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
      }
      
      .next-round-btn {
        background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
        border: none;
        padding: 15px 50px;
        font-size: 18px;
        font-weight: 600;
        border-radius: 10px;
        color: white;
        transition: all 0.3s ease;
      }
      
      .next-round-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(76, 175, 80, 0.4);
      }
      
      .next-round-btn:disabled {
        background: #e0e0e0;
        color: #9e9e9e;
        cursor: not-allowed;
        opacity: 0.6;
      }
      
      /* Completion Messages */
      .completion-message {
        background: #d4edda;
        border: 2px solid #c3e6cb;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
      }
      
      .locked-message {
        background: #fff3cd;
        border: 2px solid #ffeaa7;
        border-radius: 10px;
        padding: 15px;
        margin-top: 10px;
      }
      
      /* Final Results Podium */
      .final-results-container {
        background: white;
        border-radius: 20px;
        padding: 50px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin: 30px 0;
        text-align: center;
      }
      
      .final-results-title {
        font-size: 32px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
      }
      
      .winner-label {
        font-size: 24px;
        font-weight: 600;
        color: var(--theme-primary-color, #667eea);
        margin-bottom: 30px;
      }
      
      .crown-icon {
        font-size: 64px;
        color: #ffd700;
        margin-bottom: 20px;
      }
      
      .podium-container {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        gap: 30px;
        margin-top: 40px;
      }
      
      .podium-place {
        text-align: center;
        transition: transform 0.3s ease;
      }
      
      .podium-place:hover { transform: translateY(-5px); }
      
      .podium-place.first {
        order: 1;
        margin-bottom: 40px;
      }
      
      .podium-place.second {
        order: 0;
        margin-bottom: 10px;
      }
      
      .podium-place.third {
        order: 2;
        margin-bottom: 10px;
      }
      
      .podium-avatar-container {
        position: relative;
        margin-bottom: 15px;
      }
      
      .podium-avatar {
        width: 180px;
        height: 180px;
        border-radius: 15px;
        object-fit: cover;
        border: 4px solid #f0f0f0;
        background: #e9ecef;
      }
      
      .podium-place.first .podium-avatar {
        width: 220px;
        height: 220px;
        border-color: #ffd700;
        box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4);
      }
      
      .podium-place.second .podium-avatar,
      .podium-place.third .podium-avatar {
        width: 180px;
        height: 180px;
      }
      
      .podium-place.second .podium-avatar { border-color: #c0c0c0; }
      .podium-place.third .podium-avatar { border-color: #cd7f32; }
      
      .place-badge {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      }
      
      .place-badge.first { color: #ffd700; border: 2px solid #ffd700; }
      .place-badge.second { color: #c0c0c0; border: 2px solid #c0c0c0; }
      .place-badge.third { color: #cd7f32; border: 2px solid #cd7f32; }
      
      .podium-name {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
      }
      
      .podium-place.first .podium-name { font-size: 24px; }
      
      .podium-score {
        font-size: 18px;
        font-weight: 700;
        color: var(--theme-primary-color, #667eea);
      }
      
      .podium-place.first .podium-score {
        font-size: 22px;
        color: #ffd700;
      }
      
      /* Contestants Table Styles */
      .contestants-table {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      }
      .contestants-table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #495057;
        padding: 1rem;
      }
      .contestants-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
      }
      .contestants-table tbody tr:hover {
        background: #f8f9fa;
      }
      .contestants-table tbody td {
        padding: 1rem;
        vertical-align: middle;
      }
      .contestant-photo {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
      }
      .contestant-number-badge {
        display: inline-block;
        background: #6c757d;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
      }
      .search-filter-bar {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
      }
      .view-btn {
        padding: 0.5rem 1.25rem;
        font-size: 0.875rem;
        border-radius: 6px;
      }
    </style>
    
    <!-- Additional CSS -->
    <?= $this->renderSection('styles') ?>
</head>
<body>
    
    <!-- Header removed for cleaner look -->
    
    <!-- Main Container -->
    <?php $userRole = session()->get('user_role') ?? 'guest'; ?>
    <?php if ($userRole === 'judge'): ?>
        <!-- Judge Layout (No Container) -->
        <?= $this->renderSection('content') ?>
    <?php else: ?>
        <!-- Admin Layout (With Sidebar) -->
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <?= $this->include('partials/sidebar') ?>
                
                <!-- Main Content Area -->
                <main class="col-md-9 col-lg-10 px-md-4">
                    <?= $this->renderSection('content') ?>
                </main>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    <!-- Additional Scripts -->
    <?= $this->renderSection('scripts') ?>
    
</body>
</html>
