@extends('layouts.app')

@section('title', 'الملف الشخصي - شركة بهجة')
@section('page-title', 'الملف الشخصي')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-user me-2"></i>
            الملف الشخصي
        </h1>
    </div>

    <div class="row">
        <!-- تحديث معلومات الملف الشخصي -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        تحديث المعلومات الشخصية
                    </h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- معلومات المستخدم -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الحساب
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-circle mx-auto mb-3">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                        <h5>{{ Auth::user()->name }}</h5>
                        <span class="badge bg-primary">{{ Auth::user()->role === 'super_admin' ? 'سوبر أدمن' : (Auth::user()->role === 'admin' ? 'أدمن' : 'مندوب مبيعات') }}</span>
                    </div>
                    <hr>
                    <div class="info-item">
                        <strong>البريد الإلكتروني:</strong>
                        <p>{{ Auth::user()->email }}</p>
                    </div>
                    <div class="info-item">
                        <strong>الهاتف:</strong>
                        <p>{{ Auth::user()->phone ?? 'غير محدد' }}</p>
                    </div>
                    <div class="info-item">
                        <strong>العنوان:</strong>
                        <p>{{ Auth::user()->address ?? 'غير محدد' }}</p>
                    </div>
                    <div class="info-item">
                        <strong>تاريخ التسجيل:</strong>
                        <p>{{ Auth::user()->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- تغيير كلمة المرور -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lock me-2"></i>
                        تغيير كلمة المرور
                    </h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- حذف الحساب -->
        <div class="col-md-6 mb-4">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-trash me-2"></i>
                        حذف الحساب
                    </h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 80px;
    height: 80px;
    background: #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.info-item {
    margin-bottom: 15px;
}

.info-item strong {
    color: #495057;
    font-size: 0.9rem;
}

.info-item p {
    margin: 5px 0 0 0;
    color: #6c757d;
}
</style>
@endsection
