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
    
    <!-- Additional CSS -->
    <?= $this->renderSection('styles') ?>
</head>
<body>
    
    <!-- Header removed for cleaner look -->
    
    <!-- Main Container -->
    <div class="container-fluid">
        <div class="row">
            
            <!-- Sidebar (conditional based on role) -->
            <?= $this->include('partials/sidebar') ?>
            
            <!-- Main Content Area -->
            <main class="col-md-9 col-lg-10 px-md-4">
                
                <!-- Page Content -->
                <?= $this->renderSection('content') ?>
                
            </main>
        </div>
    </div>
    
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
