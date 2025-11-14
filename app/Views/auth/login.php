<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $title ?> - Pageant System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Global Template Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/template.css') ?>">

    <!-- Theme Variables -->
    <link rel="stylesheet" href="<?= base_url('theme.css') ?>?v=<?= time() ?>">
    
</head>
<body class="login-page">
<?php
    if (!function_exists('system_name')) {
        helper('settings');
    }
    $systemNameRaw = trim(function_exists('system_name') ? system_name() : 'Pageant Management');
    if ($systemNameRaw === '') {
        $systemNameRaw = 'Pageant Management';
    }
    $nameParts = preg_split('/\s+/', $systemNameRaw);
    $primaryName = array_shift($nameParts);
    if (!$primaryName) {
        $primaryName = $systemNameRaw;
    }
    $highlightName = !empty($nameParts) ? strtoupper(implode(' ', $nameParts)) : 'MANAGEMENT SUITE';
?>
    <div class="login-wrapper">
        <!-- LEFT SIDE - Brand & Features -->
        <div class="brand-side">
            
            <!-- Heading -->
            <div class="brand-heading">
                <h1>
                    <?= esc($primaryName) ?>
                    <span class="highlight"><?= esc($highlightName) ?></span>
                </h1>
            </div>
            
            <!-- Tagline -->
            <p class="brand-tagline">
                Where elegance meets precision—curated tools for directors, coordinators, and judges to deliver unforgettable competitions.
            </p>
            
            <!-- Features Grid -->
            <div class="features-grid">
                <!-- Contestant Management -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="feature-title">Contestants</div>
                    <div class="feature-desc">Complete profiles</div>
                </div>
                
                <!-- Judging System -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div class="feature-title">Judging</div>
                    <div class="feature-desc">Real-time scoring</div>
                </div>
                
                <!-- Results & Rankings -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div class="feature-title">Results</div>
                    <div class="feature-desc">Live rankings</div>
                </div>
                
                <!-- Security -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>
                    <div class="feature-title">Security</div>
                    <div class="feature-desc">Role-based access</div>
                </div>
            </div>
        </div>
        
        <!-- RIGHT SIDE - Login Form -->
        <div class="login-side">
            <div class="login-card">
                <!-- Login Icon -->
                <div class="login-icon">
                    <i class="bi bi-box-arrow-in-right"></i>
                </div>
                
                <!-- Heading -->
                <div class="login-heading">
                    <h2>Welcome Back</h2>
                </div>
                <p class="login-subtitle">Please sign in to continue to <?= esc($systemNameRaw) ?></p>
                
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Login Form -->
                <form action="<?= base_url('login') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Email Address</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="username" 
                            name="username" 
                            placeholder="judge"
                            value="<?= old('username') ?>"
                            required
                            autofocus
                        >
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••"
                            required
                        >
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="remember" 
                            name="remember"
                            value="1"
                        >
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-signin">
                        <i class="bi bi-arrow-right-circle-fill"></i>
                        Sign In
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
