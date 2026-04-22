<section>
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
</section>
