@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">تعديل فاتورة مبيعات رقم: {{ $invoice->invoice_number }}</h5>
                </div>
                <div class="card-body">
                    @if($errors->has('items'))
                        <div class="alert alert-danger">{{ $errors->first('items') }}</div>
                    @endif
                    <form action="{{ route('sales-invoices.update', $invoice) }}" method="POST" id="salesInvoiceForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="invoice_number" class="form-label">رقم الفاتورة <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" 
                                           id="invoice_number" name="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" required>
                                    @error('invoice_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="invoice_date" class="form-label">تاريخ الفاتورة <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                           id="invoice_date" name="invoice_date" value="{{ old('invoice_date', optional($invoice->invoice_date)->format('Y-m-d')) }}" required>
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
                                            <option value="{{ $customer->id }}" {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sales_representative_id" class="form-label">مندوب المبيعات <span class="text-danger">*</span></label>
                                    <select class="form-select @error('sales_representative_id') is-invalid @enderror" id="sales_representative_id" name="sales_representative_id" required
                                            {{ $salesRepresentatives->count() == 1 ? 'readonly style=pointer-events:none;' : '' }}>
                                        @if($salesRepresentatives->count() > 1)
                                            <option value="">اختر المندوب</option>
                                        @endif
                                        @foreach($salesRepresentatives as $rep)
                                            <option value="{{ $rep->id }}" 
                                                {{ (old('sales_representative_id', $invoice->sales_representative_id) == $rep->id || $salesRepresentatives->count() == 1) ? 'selected' : '' }}>
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
                        </div>

                        <hr>
                        <h6 class="mb-3">أصناف الفاتورة</h6>
                        
                        <div id="invoice-items">
                            @foreach($invoice->items as $index => $item)
                            <div class="row invoice-item">
                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">المنتج <span class="text-danger">*</span></label>
                                        <select class="form-select product-select" name="items[{{ $index }}][product_id]" required>
                                            <option value="">اختر المنتج</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" 
                                                        data-price="{{ $product->retail_price }}" 
                                                        data-stock="{{ $product->total_stock_units }}"
                                                        {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }} (متوفر: {{ $product->total_stock_units }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">كراتين</label>
                                        <input type="number" class="form-control quantity-cartons-input" name="items[{{ $index }}][quantity_cartons]" 
                                               value="{{ $item->quantity_cartons }}" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">قطع</label>
                                        <input type="number" class="form-control quantity-units-input" name="items[{{ $index }}][quantity_units]" 
                                               value="{{ $item->quantity_units }}" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">سعر الوحدة <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control price-input" name="items[{{ $index }}][unit_price]" 
                                               value="{{ $item->unit_price }}" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">الإجمالي</label>
                                        <input type="text" class="form-control total-input" value="{{ $item->total_price }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm d-block remove-item" {{ $index === 0 ? 'style=display:none;' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
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
                                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">ملخص الفاتورة</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td>المجموع الفرعي:</td>
                                                <td class="text-end" id="subtotal">{{ number_format($invoice->subtotal_amount, 2) }} ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>الضريبة:</td>
                                                <td class="text-end" id="tax">{{ number_format($invoice->tax_amount, 2) }} ج.م</td>
                                            </tr>
                                            <tr class="table-dark">
                                                <td><strong>الإجمالي:</strong></td>
                                                <td class="text-end"><strong id="total">{{ number_format($invoice->total_amount, 2) }} ج.م</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sales-invoices.show', $invoice) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-1"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> حفظ التغييرات
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
    let itemCount = {{ $invoice->items->count() }};

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
                input.value = '';
            }
        });
        
        // Remove hidden ID field for new items
        const hiddenId = newItem.querySelector('input[type="hidden"]');
        if (hiddenId) {
            hiddenId.remove();
        }
        
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
            if (document.querySelectorAll('.invoice-item').length > 1) {
                e.target.closest('.invoice-item').remove();
                calculateTotal();
            }
        }
    });

    // Add event listeners to existing items
    document.querySelectorAll('.invoice-item').forEach(addItemEventListeners);

    function addItemEventListeners(item) {
        const productSelect = item.querySelector('.product-select');
        const quantityInput = item.querySelector('.quantity-input');
        const priceInput = item.querySelector('.price-input');
        const totalInput = item.querySelector('.total-input');

        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                priceInput.value = selectedOption.dataset.price || '';
                calculateItemTotal(item);
            }
        });

        quantityInput.addEventListener('input', function() {
            calculateItemTotal(item);
        });

        priceInput.addEventListener('input', function() {
            calculateItemTotal(item);
        });
    }

    function calculateItemTotal(item) {
        const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(item.querySelector('.price-input').value) || 0;
        const total = quantity * price;
        
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
    }

    // Initial calculation
    calculateTotal();
});
</script>
@endsection
