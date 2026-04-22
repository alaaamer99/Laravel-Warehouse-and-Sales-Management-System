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
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">الدور</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->role === 'super_admin' ? 'سوبر أدمن' : (Auth::user()->role === 'admin' ? 'أدمن' : 'مندوب مبيعات') }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', Auth::user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                حفظ التغييرات
                            </button>

                            @if (session('status') === 'profile-updated')
                                <span class="text-success">
                                    <i class="fas fa-check me-1"></i>
                                    تم حفظ التغييرات بنجاح
                                </span>
                            @endif
                        </div>
                    </form>
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
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">كلمة المرور الحالية <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور الجديدة <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">يجب أن تحتوي على 8 أحرف على الأقل</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" required>
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>
                                تحديث كلمة المرور
                            </button>

                            @if (session('status') === 'password-updated')
                                <span class="text-success">
                                    <i class="fas fa-check me-1"></i>
                                    تم تحديث كلمة المرور بنجاح
                                </span>
                            @endif
                        </div>
                    </form>
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
                    <div class="alert alert-danger mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>تحذير:</strong> حذف الحساب عملية لا يمكن التراجع عنها. سيتم حذف جميع بياناتك نهائياً.
                    </div>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="fas fa-trash me-2"></i>
                        حذف الحساب نهائياً
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal حذف الحساب -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    تأكيد حذف الحساب
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        هل أنت متأكد من رغبتك في حذف حسابك نهائياً؟
                    </div>
                    
                    <p class="text-muted mb-3">
                        بمجرد حذف حسابك، سيتم فقدان جميع الموارد والبيانات الخاصة بك نهائياً. 
                        يرجى إدخال كلمة المرور لتأكيد رغبتك في حذف حسابك نهائياً.
                    </p>

                    <div class="mb-3">
                        <label for="delete_password" class="form-label">كلمة المرور للتأكيد <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               id="delete_password" name="password" placeholder="أدخل كلمة المرور" required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        إلغاء
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        حذف الحساب نهائياً
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
            modal.show();
        });
    </script>
@endif

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
