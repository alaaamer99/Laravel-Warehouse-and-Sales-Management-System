@extends('layouts.app')

@section('title', 'الملف الشخصي - شركة بهجة')
@section('page-title', 'الملف الشخصي')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-user me-2"></i>
            الملف الشخصي والإعدادات
        </h1>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                <i class="fas fa-user me-2"></i>الملف الشخصي
            </button>
        </li>
        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                <i class="fas fa-cogs me-2"></i>الإعدادات العامة
            </button>
        </li>
        @endif
    </ul>

    <div class="tab-content" id="profileTabsContent">
        <!-- Profile Tab -->
        <div class="tab-pane fade show active" id="profile" role="tabpanel">

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

        </div>
        <!-- End Profile Tab -->

        <!-- Settings Tab -->
        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
        <div class="tab-pane fade" id="settings" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        الإعدادات العامة للنظام
                    </h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">اسم الشركة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                       id="company_name" name="company_name" value="{{ old('company_name', $settings->company_name ?? 'شركة بهجة للمنظفات') }}" required>
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="company_phone" class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control @error('company_phone') is-invalid @enderror" 
                                       id="company_phone" name="company_phone" value="{{ old('company_phone', $settings->company_phone ?? '') }}">
                                @error('company_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control @error('company_email') is-invalid @enderror" 
                                       id="company_email" name="company_email" value="{{ old('company_email', $settings->company_email ?? '') }}">
                                @error('company_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="company_website" class="form-label">الموقع الإلكتروني</label>
                                <input type="url" class="form-control @error('company_website') is-invalid @enderror" 
                                       id="company_website" name="company_website" value="{{ old('company_website', $settings->company_website ?? '') }}">
                                @error('company_website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="company_address" class="form-label">عنوان الشركة</label>
                            <textarea class="form-control @error('company_address') is-invalid @enderror" 
                                      id="company_address" name="company_address" rows="3">{{ old('company_address', $settings->company_address ?? '') }}</textarea>
                            @error('company_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tax_number" class="form-label">الرقم الضريبي</label>
                                <input type="text" class="form-control @error('tax_number') is-invalid @enderror" 
                                       id="tax_number" name="tax_number" value="{{ old('tax_number', $settings->tax_number ?? '') }}">
                                @error('tax_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="commercial_register" class="form-label">السجل التجاري</label>
                                <input type="text" class="form-control @error('commercial_register') is-invalid @enderror" 
                                       id="commercial_register" name="commercial_register" value="{{ old('commercial_register', $settings->commercial_register ?? '') }}">
                                @error('commercial_register')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="currency" class="form-label">العملة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('currency') is-invalid @enderror" 
                                       id="currency" name="currency" value="{{ old('currency', $settings->currency ?? 'ج.م') }}" required>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="timezone" class="form-label">المنطقة الزمنية <span class="text-danger">*</span></label>
                                <select class="form-select @error('timezone') is-invalid @enderror" id="timezone" name="timezone" required>
                                    <option value="Africa/Cairo" {{ old('timezone', $settings->timezone ?? 'Africa/Cairo') == 'Africa/Cairo' ? 'selected' : '' }}>القاهرة (GMT+2)</option>
                                    <option value="Asia/Riyadh" {{ old('timezone', $settings->timezone ?? '') == 'Asia/Riyadh' ? 'selected' : '' }}>الرياض (GMT+3)</option>
                                    <option value="Asia/Dubai" {{ old('timezone', $settings->timezone ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>دبي (GMT+4)</option>
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_logo" class="form-label">شعار الشركة</label>
                                <input type="file" class="form-control @error('company_logo') is-invalid @enderror" 
                                       id="company_logo" name="company_logo" accept="image/*">
                                @error('company_logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if(isset($settings) && $settings->company_logo)
                                    <small class="form-text text-muted">الشعار الحالي: {{ basename($settings->company_logo) }}</small>
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="company_favicon" class="form-label">أيقونة الموقع (Favicon)</label>
                                <input type="file" class="form-control @error('company_favicon') is-invalid @enderror" 
                                       id="company_favicon" name="company_favicon" accept="image/*">
                                @error('company_favicon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if(isset($settings) && $settings->company_favicon)
                                    <small class="form-text text-muted">الأيقونة الحالية: {{ basename($settings->company_favicon) }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="invoice_terms" class="form-label">شروط وأحكام الفواتير</label>
                            <textarea class="form-control @error('invoice_terms') is-invalid @enderror" 
                                      id="invoice_terms" name="invoice_terms" rows="4">{{ old('invoice_terms', $settings->invoice_terms ?? '') }}</textarea>
                            @error('invoice_terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>
                                حفظ الإعدادات
                            </button>

                            @if (session('success'))
                                <span class="text-success">
                                    <i class="fas fa-check me-1"></i>
                                    {{ session('success') }}
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        <!-- End Settings Tab -->
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a tab parameter in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    if (tabParam === 'settings') {
        // Activate the settings tab
        const settingsTab = document.getElementById('settings-tab');
        const profileTab = document.getElementById('profile-tab');
        const settingsPane = document.getElementById('settings');
        const profilePane = document.getElementById('profile');
        
        if (settingsTab && profileTab && settingsPane && profilePane) {
            // Remove active class from profile tab
            profileTab.classList.remove('active');
            profilePane.classList.remove('show', 'active');
            
            // Add active class to settings tab
            settingsTab.classList.add('active');
            settingsPane.classList.add('show', 'active');
        }
    }
});
</script>

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
