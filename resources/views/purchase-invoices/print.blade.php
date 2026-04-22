@extends('print.layout')

@section('title', 'فاتورة شراء رقم ' . $invoice->invoice_number)

@section('content')
<!-- Document Title -->
<div class="document-title text-center mb-4">
    <h2 class="text-primary">
        <i class="fas fa-file-invoice-dollar me-2"></i>
        فاتورة شراء
    </h2>
    <div class="document-number">رقم الفاتورة: {{ $invoice->invoice_number }}</div>
</div>

<!-- Invoice Information -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="info-section">
            <h4 class="section-title">
                <i class="fas fa-truck me-2"></i>
                بيانات المورد
            </h4>
            <div class="info-row">
                <span class="info-label">اسم المورد:</span>
                <span class="info-value">{{ $invoice->supplier->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">رقم الهاتف:</span>
                <span class="info-value">{{ $invoice->supplier->phone ?? 'غير محدد' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">العنوان:</span>
                <span class="info-value">{{ $invoice->supplier->address ?? 'غير محدد' }}</span>
            </div>
            @if($invoice->supplier->email)
            <div class="info-row">
                <span class="info-label">البريد الإلكتروني:</span>
                <span class="info-value">{{ $invoice->supplier->email }}</span>
            </div>
            @endif
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="info-section">
            <h4 class="section-title">
                <i class="fas fa-calendar-alt me-2"></i>
                بيانات الفاتورة
            </h4>
            <div class="info-row">
                <span class="info-label">تاريخ الفاتورة:</span>
                <span class="info-value">{{ $invoice->invoice_date }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">حالة الدفع:</span>
                <span class="info-value">
                    @if($invoice->payment_status === 'paid')
                        <span class="badge bg-success">مدفوع</span>
                    @elseif($invoice->payment_status === 'partial')
                        <span class="badge bg-warning">مدفوع جزئي</span>
                    @else
                        <span class="badge bg-danger">غير مدفوع</span>
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">تاريخ الإنشاء:</span>
                <span class="info-value">{{ $invoice->created_at->format('Y-m-d H:i') }}</span>
            </div>
            @if($invoice->user)
            <div class="info-row">
                <span class="info-label">المستخدم:</span>
                <span class="info-value">{{ $invoice->user->name }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Invoice Items -->
<div class="table-container">
    <h4 class="section-title mb-3">
        <i class="fas fa-list me-2"></i>
        تفاصيل الأصناف
    </h4>
    
    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 25%">اسم الصنف</th>
                <th style="width: 10%">علب</th>
                <th style="width: 10%">قطع</th>
                <th style="width: 15%">السعر ({{ $globalSettings->currency ?? 'ج.م' }})</th>
                <th style="width: 15%">الإجمالي ({{ $globalSettings->currency ?? 'ج.م' }})</th>
                <th style="width: 20%">ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-start">{{ $item->product->name }}</td>
                <td>{{ $item->quantity_cartons }}</td>
                <td>{{ $item->quantity_units }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->total_price, 2) }}</td>
                <td class="text-start">{{ $item->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Totals Section -->
<div class="totals-section">
    <div class="total-row">
        <span class="total-label">إجمالي المبلغ:</span>
        <span class="total-value">{{ number_format($invoice->total_amount, 2) }} {{ $globalSettings->currency ?? 'ج.م' }}</span>
    </div>
    
    @if($invoice->discount_amount > 0)
    <div class="total-row">
        <span class="total-label">الخصم:</span>
        <span class="total-value">{{ number_format($invoice->discount_amount, 2) }} {{ $globalSettings->currency ?? 'ج.م' }}</span>
    </div>
    @endif
    
    @if($invoice->tax_amount > 0)
    <div class="total-row">
        <span class="total-label">الضريبة:</span>
        <span class="total-value">{{ number_format($invoice->tax_amount, 2) }} {{ $globalSettings->currency ?? 'ج.م' }}</span>
    </div>
    @endif
    
    <div class="total-row final-total">
        <span class="total-label">المبلغ النهائي:</span>
        <span class="total-value">{{ number_format($invoice->final_amount ?? $invoice->total_amount, 2) }} {{ $globalSettings->currency ?? 'ج.م' }}</span>
    </div>
</div>

<!-- Notes Section -->
@if($invoice->notes)
<div class="notes-section">
    <h4 class="section-title">
        <i class="fas fa-sticky-note me-2"></i>
        ملاحظات
    </h4>
    <div class="notes-content">
        {{ $invoice->notes }}
    </div>
</div>
@endif

<!-- Terms and Conditions -->
@if($globalSettings && $globalSettings->invoice_terms)
<div class="terms-section">
    <h5>الشروط والأحكام:</h5>
    <p>{{ $globalSettings->invoice_terms }}</p>
</div>
@endif

<!-- Footer -->
<div class="footer-section">
    <div class="signature-area">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">توقيع المورد</div>
        </div>
    </div>
    
    <div class="signature-area">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">توقيع المسؤول</div>
        </div>
    </div>
</div>
@endsection

@section('additional_styles')
<style>
    .badge {
        font-size: 0.8rem;
        padding: 5px 10px;
    }
    
    .bg-success { background-color: #28a745 !important; color: white; }
    .bg-warning { background-color: #ffc107 !important; color: black; }
    .bg-danger { background-color: #dc3545 !important; color: white; }
</style>
@endsection
