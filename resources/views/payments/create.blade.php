@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">إضافة دفعة جديدة</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sales_invoice_id" class="form-label">الفاتورة <span class="text-danger">*</span></label>
                                    <select class="form-select @error('sales_invoice_id') is-invalid @enderror" 
                                            id="sales_invoice_id" name="sales_invoice_id" required>
                                        <option value="">اختر الفاتورة</option>
                                        @foreach($invoices as $invoice)
                                            <option value="{{ $invoice->id }}" 
                                                    data-total="{{ $invoice->total_amount }}"
                                                    data-paid="{{ $invoice->payments->sum('amount') }}"
                                                    data-customer="{{ $invoice->customer->name }}"
                                                    {{ old('sales_invoice_id', request('invoice_id')) == $invoice->id ? 'selected' : '' }}>
                                                {{ $invoice->invoice_number }} - {{ $invoice->customer->name }} 
                                                ({{ number_format($invoice->total_amount - $invoice->payments->sum('amount'), 2) }} ج.م متبقي)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sales_invoice_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">تاريخ الدفع <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                           id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">المبلغ <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" min="0" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" name="payment_method" required>
                                        <option value="">اختر طريقة الدفع</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>نقداً</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                        <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>شيك</option>
                                        <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="reference_number" class="form-label">رقم المرجع</label>
                                    <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                           id="reference_number" name="reference_number" value="{{ old('reference_number') }}">
                                    @error('reference_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">ملاحظات</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Details Card -->
                        <div class="row" id="invoice-details" style="display: none;">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">تفاصيل الفاتورة</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p><strong>العميل:</strong> <span id="customer-name">-</span></p>
                                            </div>
                                            <div class="col-md-3">
                                                <p><strong>إجمالي الفاتورة:</strong> <span id="invoice-total">0.00</span> ج.م</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p><strong>المدفوع سابقاً:</strong> <span id="paid-amount">0.00</span> ج.م</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p><strong>المتبقي:</strong> <span id="remaining-amount" class="text-danger">0.00</span> ج.م</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-1"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> حفظ الدفعة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const invoiceSelect = document.getElementById('sales_invoice_id');
    const amountInput = document.getElementById('amount');
    const invoiceDetails = document.getElementById('invoice-details');
    
    invoiceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            // Show invoice details
            invoiceDetails.style.display = 'block';
            
            // Update details
            const total = parseFloat(selectedOption.dataset.total) || 0;
            const paid = parseFloat(selectedOption.dataset.paid) || 0;
            const remaining = total - paid;
            
            document.getElementById('customer-name').textContent = selectedOption.dataset.customer || '-';
            document.getElementById('invoice-total').textContent = total.toFixed(2);
            document.getElementById('paid-amount').textContent = paid.toFixed(2);
            document.getElementById('remaining-amount').textContent = remaining.toFixed(2);
            
            // Set max amount to remaining amount
            amountInput.setAttribute('max', remaining);
            amountInput.value = remaining.toFixed(2);
        } else {
            invoiceDetails.style.display = 'none';
            amountInput.removeAttribute('max');
            amountInput.value = '';
        }
    });
    
    // Trigger change event if invoice is pre-selected
    if (invoiceSelect.value) {
        invoiceSelect.dispatchEvent(new Event('change'));
    }
    
    // Validate amount
    amountInput.addEventListener('input', function() {
        const max = parseFloat(this.getAttribute('max'));
        const value = parseFloat(this.value);
        
        if (max && value > max) {
            this.setCustomValidity('المبلغ المدخل أكبر من المبلغ المتبقي');
        } else if (value <= 0) {
            this.setCustomValidity('يجب أن يكون المبلغ أكبر من صفر');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endsection
