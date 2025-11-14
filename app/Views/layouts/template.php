<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $this->renderSection('title') ?> - <?= esc(system_name()) ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Global Template Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/template.css') ?>">
    
    <!-- Dynamic Theme CSS (Auto-applies user-selected colors) -->
    <link rel="stylesheet" href="<?= base_url('theme.css') ?>?v=<?= time() ?>">
    
    <!-- Global overrides moved to template.css -->
    
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

        // Apply progress widths defined via data attributes (avoids inline styles)
        (function applyProgressWidths() {
            const bars = document.querySelectorAll('[data-progress-width]');
            bars.forEach(function(bar) {
                const width = bar.getAttribute('data-progress-width');
                if (width !== null) {
                    bar.style.width = width.replace('%', '') + '%';
                }
            });
        })();

        document.addEventListener('DOMContentLoaded', function() {
            const bell = document.getElementById('notificationBell');
            const count = document.getElementById('notificationCount');
            if (bell && count) {
                bell.addEventListener('click', function() {
                    count.remove();
                }, { once: true });
            }
        });
    </script>
    
    <!-- Additional Scripts -->
    <?= $this->renderSection('scripts') ?>
    
</body>
</html>

