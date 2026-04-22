@extends('layouts.app')

@section('title', 'إضافة فاتورة شراء جديدة - شركة بهجة')
@section('page-title', 'إضافة فاتورة شراء جديدة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        إضافة فاتورة شراء جديدة
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('purchase-invoices.store') }}" method="POST" id="purchaseInvoiceForm">
                        @csrf
                        
                        <!-- Invoice Header -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="invoice_number" class="form-label">
                                    <i class="fas fa-receipt me-1"></i>
                                    رقم الفاتورة *
                                </label>
                                <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" 
                                       id="invoice_number" name="invoice_number" value="{{ old('invoice_number', 'PUR-' . date('Y') . '-' . str_pad(1, 6, '0', STR_PAD_LEFT)) }}" required>
                                @error('invoice_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="supplier_id" class="form-label">
                                    <i class="fas fa-truck me-1"></i>
                                    المورد *
                                </label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                        id="supplier_id" name="supplier_id" required>
                                    <option value="">اختر المورد</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="invoice_date" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>
                                    تاريخ الفاتورة *
                                </label>
                                <input type="date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                       id="invoice_date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                @error('invoice_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="payment_status" class="form-label">
                                    <i class="fas fa-credit-card me-1"></i>
                                    حالة الدفع
                                </label>
                                <select class="form-select @error('payment_status') is-invalid @enderror" 
                                        id="payment_status" name="payment_status">
                                    <option value="pending" {{ old('payment_status', 'pending') == 'pending' ? 'selected' : '' }}>معلقة</option>
                                    <option value="partial" {{ old('payment_status') == 'partial' ? 'selected' : '' }}>مدفوعة جزئياً</option>
                                    <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>مدفوعة بالكامل</option>
                                </select>
                                @error('payment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>
                                    ملاحظات
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Invoice Items -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">
                                    <i class="fas fa-list me-2"></i>
                                    أصناف الفاتورة
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="addItem">
                                    <i class="fas fa-plus me-1"></i>
                                    إضافة صنف
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="itemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30%">المنتج</th>
                                            <th width="15%">الكمية (كرتونة)</th>
                                            <th width="15%">الكمية (قطعة)</th>
                                            <th width="15%">سعر الوحدة</th>
                                            <th width="20%">المبلغ الفرعي</th>
                                            <th width="5%">حذف</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsTableBody">
                                        <!-- Items will be added here dynamically -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-dark">
                                            <td colspan="4"><strong>المجموع الإجمالي</strong></td>
                                            <td><strong id="totalAmount">0.00 ج.م</strong></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <input type="hidden" name="total_amount" id="hiddenTotalAmount" value="0">

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('purchase-invoices.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ الفاتورة
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = 0;
    const products = @json($products);
    
    // Add item function
    document.getElementById('addItem').addEventListener('click', function() {
        addItem();
    });
    
    // Add initial item
    addItem();
    
    function addItem() {
        itemCount++;
        const tbody = document.getElementById('itemsTableBody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select class="form-select" name="items[${itemCount}][product_id]" required onchange="updatePrice(this, ${itemCount})">
                    <option value="">اختر المنتج</option>
                    ${products.map(product => `
                        <option value="${product.id}" data-price="${product.purchase_price}" data-units="${product.units_per_carton}">
                            ${product.name}
                        </option>
                    `).join('')}
                </select>
            </td>
            <td>
                <input type="number" class="form-control" name="items[${itemCount}][quantity_cartons]" value="0" min="0" onchange="calculateSubtotal(${itemCount})">
            </td>
            <td>
                <input type="number" class="form-control" name="items[${itemCount}][quantity_units]" value="0" min="0" onchange="calculateSubtotal(${itemCount})">
            </td>
            <td>
                <input type="number" step="0.01" class="form-control" name="items[${itemCount}][unit_price]" value="0" min="0" onchange="calculateSubtotal(${itemCount})">
            </td>
            <td>
                <span class="subtotal fw-bold">0.00 ج.م</span>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    }
    
    window.updatePrice = function(select, itemIndex) {
        const option = select.options[select.selectedIndex];
        const price = option.getAttribute('data-price') || 0;
        const priceInput = document.querySelector(`input[name="items[${itemIndex}][unit_price]"]`);
        priceInput.value = price;
        calculateSubtotal(itemIndex);
    };
    
    window.calculateSubtotal = function(itemIndex) {
        const cartons = parseInt(document.querySelector(`input[name="items[${itemIndex}][quantity_cartons]"]`).value) || 0;
        const units = parseInt(document.querySelector(`input[name="items[${itemIndex}][quantity_units]"]`).value) || 0;
        const unitPrice = parseFloat(document.querySelector(`input[name="items[${itemIndex}][unit_price]"]`).value) || 0;
        
        // Get units per carton for selected product
        const productSelect = document.querySelector(`select[name="items[${itemIndex}][product_id]"]`);
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const unitsPerCarton = parseInt(selectedOption.getAttribute('data-units')) || 1;
        
        const totalUnits = (cartons * unitsPerCarton) + units;
        const subtotal = totalUnits * unitPrice;
        
        const subtotalSpan = document.querySelector(`tr:nth-child(${itemIndex}) .subtotal`);
        subtotalSpan.textContent = subtotal.toFixed(2) + ' ج.م';
        
        calculateTotal();
    };
    
    window.removeItem = function(button) {
        button.closest('tr').remove();
        calculateTotal();
    };
    
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(function(span) {
            const value = parseFloat(span.textContent.replace(' ج.م', '')) || 0;
            total += value;
        });
        
        document.getElementById('totalAmount').textContent = total.toFixed(2) + ' ج.م';
        document.getElementById('hiddenTotalAmount').value = total.toFixed(2);
    }
});
</script>
@endpush
@endsection
