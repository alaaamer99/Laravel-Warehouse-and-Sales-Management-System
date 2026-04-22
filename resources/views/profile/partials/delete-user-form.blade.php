<section>
    <div class="alert alert-danger mb-3">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>تحذير:</strong> حذف الحساب عملية لا يمكن التراجع عنها. سيتم حذف جميع بياناتك نهائياً.
    </div>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        <i class="fas fa-trash me-2"></i>
        حذف الحساب نهائياً
    </button>

    <!-- Modal -->
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
                            <label for="password" class="form-label">كلمة المرور للتأكيد <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                                   id="password" name="password" placeholder="أدخل كلمة المرور" required>
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
</section>
