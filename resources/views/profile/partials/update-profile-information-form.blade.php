<section>
    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <small class="text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            بريدك الإلكتروني غير مؤكد.
                        </small>
                        <button form="send-verification" class="btn btn-link btn-sm p-0 text-decoration-underline">
                            اضغط هنا لإعادة إرسال رابط التأكيد
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <small class="text-success d-block mt-1">
                                <i class="fas fa-check me-1"></i>
                                تم إرسال رابط تأكيد جديد إلى بريدك الإلكتروني.
                            </small>
                        @endif
                    </div>
                @endif
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

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;">
        @csrf
    </form>
</section>
