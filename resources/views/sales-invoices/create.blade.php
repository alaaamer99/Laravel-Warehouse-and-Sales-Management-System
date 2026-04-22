@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">إضافة فاتورة مبيعات</h5>
                </div>
                <div class="card-body">
                    @if($errors->has('items'))
                        <div class="alert alert-danger">{{ $errors->first('items') }}</div>
                    @endif
                    <form action="{{ route('sales-invoices.store') }}" method="POST" id="salesInvoiceForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="invoice_number" class="form-label">رقم الفاتورة <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" 
                                           id="invoice_number" name="invoice_number" value="{{ old('invoice_number', 'INV-' . date('YmdHis')) }}" required>
                                    @error('invoice_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="invoice_date" class="form-label">تاريخ الفاتورة <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                           id="invoice_date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                    @error('invoice_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">العميل <span class="text-danger">*</span></label>
                                    <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                        <option value="">اختر العميل</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sales_representative_id" class="form-label">مندوب المبيعات <span class="text-danger">*</span></label>
                                    <select class="form-select @error('sales_representative_id') is-invalid @enderror" id="sales_representative_id" name="sales_representative_id" required 
                                            {{ $salesRepresentatives->count() == 1 ? 'readonly style=pointer-events:none;' : '' }}>
                                        @if($salesRepresentatives->count() > 1)
                                            <option value="">اختر المندوب</option>
                                        @endif
                                        @foreach($salesRepresentatives as $rep)
                                            <option value="{{ $rep->id }}" 
                                                {{ (old('sales_representative_id') == $rep->id || $salesRepresentatives->count() == 1) ? 'selected' : '' }}>
                                                {{ $rep->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($salesRepresentatives->count() == 1)
                                        <small class="form-text text-muted">تم تحديد مندوب المبيعات تلقائياً</small>
                                    @endif
                                    @error('sales_representative_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="payment_status" class="form-label">حالة الدفع</label>
                                    <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status">
                                        <option value="pending" {{ old('payment_status', 'pending') == 'pending' ? 'selected' : '' }}>معلقة</option>
                                        <option value="partial" {{ old('payment_status') == 'partial' ? 'selected' : '' }}>مدفوعة جزئياً</option>
                                        <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>مدفوعة بالكامل</option>
                                    </select>
                                    @error('payment_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="payment_type" class="form-label">نوع الدفع</label>
                                    <select class="form-select @error('payment_type') is-invalid @enderror" id="payment_type" name="payment_type">
                                        <option value="cash" {{ old('payment_type', 'cash') == 'cash' ? 'selected' : '' }}>نقدي</option>
                                        <option value="credit" {{ old('payment_type') == 'credit' ? 'selected' : '' }}>آجل</option>
                                    </select>
                                    @error('payment_type')
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

                        <hr>
                        <h6 class="mb-3">أصناف الفاتورة</h6>
                        
                        <div id="invoice-items">
                            <div class="row invoice-item">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">المنتج <span class="text-danger">*</span></label>
                                        <select class="form-select product-select" name="items[0][product_id]" required>
                                            <option value="">اختر المنتج</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" 
                                                        data-price="{{ $product->retail_price }}" 
                                                        data-stock="{{ $product->total_stock_units }}"
                                                        data-units-per-carton="{{ $product->units_per_carton }}">
                                                    {{ $product->name }} (متوفر: {{ $product->total_stock_units }} قطعة)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">عدد الكراتين</label>
                                        <input type="number" class="form-control cartons-input" name="items[0][quantity_cartons]" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">عدد القطع</label>
                                        <input type="number" class="form-control units-input" name="items[0][quantity_units]" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">سعر القطعة <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control price-input" name="items[0][unit_price]" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">الإجمالي</label>
                                        <input type="text" class="form-control total-input" readonly>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm d-block remove-item" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-success btn-sm" id="add-item">
                                    <i class="fas fa-plus me-1"></i> إضافة صنف
                                </button>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">ملاحظات</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">ملخص الفاتورة</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td>المجموع الفرعي:</td>
                                                <td class="text-end" id="subtotal">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>الضريبة (0%):</td>
                                                <td class="text-end" id="tax">0.00 ج.م</td>
                                            </tr>
                                            <tr class="table-dark">
                                                <td><strong>الإجمالي:</strong></td>
                                                <td class="text-end"><strong id="total">0.00 ج.م</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sales-invoices.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-1"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> حفظ الفاتورة
                            </button>
                        </div>
                        
                        <input type="hidden" name="total_amount" value="0">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = 1;

    // Add new item
    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('invoice-items');
        const newItem = container.querySelector('.invoice-item').cloneNode(true);
        
        // Update names and clear values
        newItem.querySelectorAll('select, input').forEach(function(input) {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, '[' + itemCount + ']'));
            }
            if (input.type !== 'hidden') {
                input.value = input.classList.contains('cartons-input') || input.classList.contains('units-input') ? '0' : '';
            }
        });
        
        // Show remove button
        newItem.querySelector('.remove-item').style.display = 'block';
        
        container.appendChild(newItem);
        itemCount++;
        
        // Add event listeners to new item
        addItemEventListeners(newItem);
    });

    // Remove item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
            e.target.closest('.invoice-item').remove();
            calculateTotal();
        }
    });

    // Add event listeners to existing items
    document.querySelectorAll('.invoice-item').forEach(addItemEventListeners);

    function addItemEventListeners(item) {
        const productSelect = item.querySelector('.product-select');
        const cartonsInput = item.querySelector('.cartons-input');
        const unitsInput = item.querySelector('.units-input');
        const priceInput = item.querySelector('.price-input');
        const totalInput = item.querySelector('.total-input');

        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                priceInput.value = selectedOption.dataset.price || '';
                calculateItemTotal(item);
            }
        });

        cartonsInput.addEventListener('input', function() {
            calculateItemTotal(item);
        });

        unitsInput.addEventListener('input', function() {
            calculateItemTotal(item);
        });

        priceInput.addEventListener('input', function() {
            calculateItemTotal(item);
        });
    }

    function calculateItemTotal(item) {
        const productSelect = item.querySelector('.product-select');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const unitsPerCarton = parseInt(selectedOption.dataset.unitsPerCarton) || 1;
        
        const cartons = parseInt(item.querySelector('.cartons-input').value) || 0;
        const units = parseInt(item.querySelector('.units-input').value) || 0;
        const price = parseFloat(item.querySelector('.price-input').value) || 0;
        
        const totalUnits = (cartons * unitsPerCarton) + units;
        const total = totalUnits * price;
        
        item.querySelector('.total-input').value = total.toFixed(2);
        calculateTotal();
    }

    function calculateTotal() {
        let subtotal = 0;
        document.querySelectorAll('.total-input').forEach(function(input) {
            subtotal += parseFloat(input.value) || 0;
        });
        
        const tax = 0; // No tax for now
        const total = subtotal + tax;
        
        document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' ج.م';
        document.getElementById('tax').textContent = tax.toFixed(2) + ' ج.م';
        document.getElementById('total').textContent = total.toFixed(2) + ' ج.م';
        
        // Update hidden total amount input if exists
        const totalAmountInput = document.querySelector('input[name="total_amount"]');
        if (totalAmountInput) {
            totalAmountInput.value = total.toFixed(2);
        }
    }

    // Add form validation
    document.getElementById('salesInvoiceForm').addEventListener('submit', function(e) {
        let hasValidItems = false;
        document.querySelectorAll('.invoice-item').forEach(function(item) {
            const productSelect = item.querySelector('.product-select');
            const cartonsInput = item.querySelector('.cartons-input');
            const unitsInput = item.querySelector('.units-input');
            const priceInput = item.querySelector('.price-input');
            
            if (productSelect.value && priceInput.value && ((cartonsInput.value && cartonsInput.value > 0) || (unitsInput.value && unitsInput.value > 0))) {
                hasValidItems = true;
            }
        });
        
        if (!hasValidItems) {
            e.preventDefault();
            alert('يجب إضافة صنف واحد على الأقل بكمية صحيحة');
        }
    });
});
</script>
@endsection
