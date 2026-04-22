<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> تسجيل الدخول -  </title>

    <!-- Bootstrap 5 RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="login-card">
                    <div class="row g-0">


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
