<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>تسجيل الدخول - شركة بهجة</title>
    
    <!-- Bootstrap 5 RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }
        
        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .login-right {
            padding: 60px 40px;
        }
        
        .company-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 2rem;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: transform 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            color: white;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .test-accounts {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .test-account {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #667eea;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .test-account:hover {
            transform: translateX(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .test-account:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="login-card">
                    <div class="row g-0">
                        <!-- Left Side -->
                        <div class="col-md-6 login-left">
                            <div class="company-logo">
                                <i class="fas fa-store"></i>
                            </div>
                            <h2 class="mb-3">مرحباً بك في شركة بهجة</h2>
                            <p class="lead mb-4">نظام إدارة المخازن ومندوبي المبيعات</p>
                            <div class="features">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle me-3"></i>
                                    <span>إدارة المخازن والأصناف</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle me-3"></i>
                                    <span>متابعة مندوبي المبيعات</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle me-3"></i>
                                    <span>تقارير مفصلة وشاملة</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side -->
                        <div class="col-md-6 login-right">
                            <h3 class="mb-4 text-center">تسجيل الدخول</h3>
                            
                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="alert alert-success mb-4">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Email Address -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>
                                        البريد الإلكتروني
                                    </label>
                                    <input id="email" class="form-control @error('email') is-invalid @enderror" 
                                           type="email" name="email" value="{{ old('email') }}" 
                                           required autofocus autocomplete="username">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>
                                        كلمة المرور
                                    </label>
                                    <input id="password" class="form-control @error('password') is-invalid @enderror"
                                           type="password" name="password" required autocomplete="current-password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Remember Me -->
                                <div class="mb-3 form-check">
                                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                    <label for="remember_me" class="form-check-label">
                                        تذكرني
                                    </label>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    @if (Route::has('password.request'))
                                        <a class="text-decoration-none" href="{{ route('password.request') }}">
                                            نسيت كلمة المرور؟
                                        </a>
                                    @endif

                                    <button type="submit" class="btn btn-login">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        تسجيل الدخول
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Test Accounts -->
                            <div class="test-accounts">
                                <h6 class="mb-3">
                                    <i class="fas fa-users me-2"></i>
                                    حسابات تجريبية
                                </h6>
                                
                                <div class="test-account" onclick="fillLogin('admin@bahja.com', 'password')">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>المدير العام</strong>
                                            <br>
                                            <small class="text-muted">admin@bahja.com</small>
                                        </div>
                                        <span class="badge bg-danger">Super Admin</span>
                                    </div>
                                </div>
                                
                                <div class="test-account" onclick="fillLogin('manager@bahja.com', 'password')">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>مدير النظام</strong>
                                            <br>
                                            <small class="text-muted">manager@bahja.com</small>
                                        </div>
                                        <span class="badge bg-warning">Admin</span>
                                    </div>
                                </div>
                                
                                <div class="test-account" onclick="fillLogin('sales@bahja.com', 'password')">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>مندوب المبيعات</strong>
                                            <br>
                                            <small class="text-muted">sales@bahja.com</small>
                                        </div>
                                        <span class="badge bg-info">Sales Rep</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
