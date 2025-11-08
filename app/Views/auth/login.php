<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Pageant System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        .login-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }
        
        /* LEFT SIDE - Brand & Features */
        .brand-side {
            background: linear-gradient(135deg, #e8eaf6 0%, #f3e5f5 50%, #e1f5fe 100%);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .brand-logo {
            display: inline-flex;
            align-items: center;
            background: white;
            padding: 12px 24px;
            border-radius: 25px;
            margin-bottom: 50px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            width: fit-content;
        }
        
        .brand-logo i {
            color: #667eea;
            font-size: 1.5rem;
            margin-right: 10px;
        }
        
        .brand-logo span {
            font-weight: 600;
            color: #333;
            font-size: 1rem;
        }
        
        .brand-heading {
            margin-bottom: 15px;
        }
        
        .brand-heading h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1.2;
            margin: 0;
        }
        
        .brand-heading .highlight {
            color: #667eea;
            display: block;
        }
        
        .brand-tagline {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 50px;
            line-height: 1.6;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .feature-card {
            text-align: center;
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: #667eea;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            transition: all 0.3s;
        }
        
        .feature-icon i {
            font-size: 2rem;
            color: white;
        }
        
        .feature-card:hover .feature-icon {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .feature-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        
        .feature-desc {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* RIGHT SIDE - Login Form */
        .login-side {
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        
        .login-card {
            background: white;
            border-radius: 30px;
            padding: 50px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            max-width: 450px;
            width: 100%;
        }
        
        .login-icon {
            width: 80px;
            height: 80px;
            background: #667eea;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }
        
        .login-icon i {
            font-size: 2.5rem;
            color: white;
        }
        
        .login-heading {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .login-heading h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }
        
        .login-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 35px;
            font-size: 0.95rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .form-control {
            background: #f0ebf8;
            border: none;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            background: #e8def7;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        .form-control::placeholder {
            color: #9ca3af;
        }
        
        .form-check {
            margin: 20px 0;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 0.15em;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-check-label {
            color: #6c757d;
            margin-left: 5px;
            font-size: 0.9rem;
        }
        
        .btn-signin {
            width: 100%;
            background: #667eea;
            border: none;
            color: white;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-signin:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }
            
            .brand-side {
                min-height: 40vh;
                padding: 40px;
            }
            
            .brand-heading h1 {
                font-size: 2.5rem;
            }
            
            .features-grid {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- LEFT SIDE - Brand & Features -->
        <div class="brand-side">
            <!-- Logo -->
            <div class="brand-logo">
                <i class="bi bi-gem"></i>
                <span>Rimmc Palaro</span>
            </div>
            
            <!-- Heading -->
            <div class="brand-heading">
                <h1>
                    Pageant
                    <span class="highlight">Management</span>
                </h1>
            </div>
            
            <!-- Tagline -->
            <p class="brand-tagline">
                Streamlined event management for modern<br>
                pageant organizers and judges
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
                <p class="login-subtitle">Please sign in to continue</p>
                
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
