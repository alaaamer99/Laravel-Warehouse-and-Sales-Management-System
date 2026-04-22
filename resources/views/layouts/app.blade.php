<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $globalSettings->company_name ?? config('app.name', 'بهجة للمنظفات'))</title>

    <!-- Favicon -->
    @if($globalSettings && $globalSettings->favicon_url)
        <link rel="icon" type="image/x-icon" href="{{ $globalSettings->favicon_url }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ $globalSettings->favicon_url }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Bootstrap 5 RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: none;
        }
        
        .table {
            border-radius: 0.375rem;
            overflow: hidden;
        }
        
        .btn {
            border-radius: 0.375rem;
        }
        
        .form-control, .form-select {
            border-radius: 0.375rem;
        }
        
        .alert {
            border-radius: 0.375rem;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stats-card .stats-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
            border-left: 1px solid #dee2e6;
        }
        
        .main-content {
            padding: 2rem;
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="min-vh-100">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="main-content">
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>يوجد أخطاء في البيانات المدخلة:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Confirm delete actions
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    if (!confirm('هل أنت متأكد من عملية الحذف؟ لا يمكن التراجع عن هذا الإجراء.')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
