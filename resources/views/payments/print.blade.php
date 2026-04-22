@extends('print.layout')

@section('title', 'إيصال دفع رقم ' . $payment->id)

@section('content')
<!-- Document Title -->
<div class="document-title text-center mb-4">
    <h2 class="text-info">
        <i class="fas fa-receipt me-2"></i>
        إيصال دفع
    </h2>
    <div class="document-number">رقم الإيصال: {{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
</div>

<!-- Payment Information -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="info-section">
            <h4 class="section-title">
                <i class="fas fa-user me-2"></i>
                بيانات العميل
            </h4>
            <div class="info-row">
                <span class="info-label">اسم العميل:</span>
                <span class="info-value">{{ $payment->salesInvoice->customer->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">رقم الهاتف:</span>
                <span class="info-value">{{ $payment->salesInvoice->customer->phone ?? 'غير محدد' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">العنوان:</span>
                <span class="info-value">{{ $payment->salesInvoice->customer->address ?? 'غير محدد' }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="info-section">
            <h4 class="section-title">
                <i class="fas fa-money-bill-wave me-2"></i>
                بيانات الدفع
            </h4>
            <div class="info-row">
                <span class="info-label">تاريخ الدفع:</span>
                <span class="info-value">{{ $payment->payment_date }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">طريقة الدفع:</span>
                <span class="info-value">
                    @if($payment->payment_method === 'cash')
                        <i class="fas fa-money-bill text-success"></i> نقدي
                    @elseif($payment->payment_method === 'bank_transfer')
                        <i class="fas fa-university text-primary"></i> تحويل بنكي
                    @elseif($payment->payment_method === 'check')
                        <i class="fas fa-file-invoice text-warning"></i> شيك
                    @else
                        {{ $payment->payment_method }}
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">المندوب:</span>
                <span class="info-value">{{ $payment->salesInvoice->salesRepresentative->name ?? 'غير محدد' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">تاريخ الإنشاء:</span>
                <span class="info-value">{{ $payment->created_at->format('Y-m-d H:i') }}</span>
            </div>
            @if($payment->user)
            <div class="info-row">
                <span class="info-label">المستخدم:</span>
                <span class="info-value">{{ $payment->user->name }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Invoice Information -->
<div class="invoice-info-section mb-4">
    <h4 class="section-title">
        <i class="fas fa-file-invoice me-2"></i>
        بيانات الفاتورة المرتبطة
    </h4>
    
    <div class="row">
        <div class="col-md-12">
            <div class="invoice-details p-3" style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px;">
                <div class="row">
                    <div class="col-md-4">
                        <div class="detail-item">
                            <strong>رقم الفاتورة:</strong><br>
                            {{ $payment->salesInvoice->invoice_number }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-item">
                            <strong>تاريخ الفاتورة:</strong><br>
                            {{ $payment->salesInvoice->invoice_date }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-item">
                            <strong>إجمالي الفاتورة:</strong><br>
                            {{ number_format($payment->salesInvoice->total_amount, 2) }} {{ $globalSettings->currency ?? 'ج.م' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Amount -->
<div class="payment-amount-section text-center mb-4">
    <div class="amount-box p-4" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h3 class="mb-2">
            <i class="fas fa-dollar-sign me-2"></i>
            المبلغ المدفوع
        </h3>
        <div class="amount-value" style="font-size: 2.5rem; font-weight: bold;">
            {{ number_format($payment->amount, 2) }} {{ $globalSettings->currency ?? 'ج.م' }}
        </div>
        <div class="amount-words mt-2" style="font-size: 1.1rem; opacity: 0.9;">
            {{ $payment->amount_in_words ?? 'المبلغ بالأرقام فقط' }}
        </div>
    </div>
</div>

<!-- Payment Details Table -->
<div class="table-container">
    <h4 class="section-title mb-3">
        <i class="fas fa-table me-2"></i>
        تفاصيل الدفع
    </h4>
    
    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 25%">البيان</th>
                <th style="width: 75%">القيمة</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>رقم الإيصال</strong></td>
                <td>{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td><strong>تاريخ الدفع</strong></td>
                <td>{{ $payment->payment_date }}</td>
            </tr>
            <tr>
                <td><strong>المبلغ المدفوع</strong></td>
                <td>{{ number_format($payment->amount, 2) }} {{ $globalSettings->currency ?? 'ج.م' }}</td>
            </tr>
            <tr>
                <td><strong>طريقة الدفع</strong></td>
                <td>
                    @if($payment->payment_method === 'cash')
                        نقدي
                    @elseif($payment->payment_method === 'bank_transfer')
                        تحويل بنكي
                    @elseif($payment->payment_method === 'check')
                        شيك
                    @else
                        {{ $payment->payment_method }}
                    @endif
                </td>
            </tr>
            @if($payment->reference_number)
            <tr>
                <td><strong>رقم المرجع</strong></td>
                <td>{{ $payment->reference_number }}</td>
            </tr>
            @endif
            <tr>
                <td><strong>اسم العميل</strong></td>
                <td>{{ $payment->salesInvoice->customer->name }}</td>
            </tr>
            <tr>
                <td><strong>رقم الفاتورة</strong></td>
                <td>{{ $payment->salesInvoice->invoice_number }}</td>
            </tr>
            <tr>
                <td><strong>المندوب</strong></td>
                <td>{{ $payment->salesInvoice->salesRepresentative->name ?? 'غير محدد' }}</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Notes Section -->
@if($payment->notes)
<div class="notes-section">
    <h4 class="section-title">
        <i class="fas fa-sticky-note me-2"></i>
        ملاحظات
    </h4>
    <div class="notes-content">
        {{ $payment->notes }}
    </div>
</div>
@endif

<!-- Important Notice -->
<div class="notice-section mt-4 p-3" style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px;">
    <h5 class="text-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        تنبيه مهم
    </h5>
    <p class="mb-0">هذا الإيصال يثبت استلام المبلغ المذكور أعلاه من العميل المذكور في التاريخ المحدد. يُرجى الاحتفاظ بهذا الإيصال كمستند رسمي.</p>
</div>

<!-- Footer -->
<div class="footer-section">
    <div class="signature-area">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">توقيع العميل</div>
        </div>
    </div>
    
    <div class="signature-area">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">توقيع المحاسب</div>
        </div>
    </div>
    
    <div class="signature-area">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">ختم الشركة</div>
        </div>
    </div>
</div>
@endsection

@section('additional_styles')
<style>
    .invoice-info-section {
        border-top: 2px solid #007bff;
        padding-top: 20px;
    }
    
    .detail-item {
        margin-bottom: 15px;
        font-size: 0.95rem;
    }
    
    .amount-box {
        margin: 20px 0;
    }
    
    .payment-amount-section {
        border: 3px solid #28a745;
        border-radius: 15px;
        margin: 30px 0;
        background: #f8f9fa;
    }
    
    .notice-section {
        margin-top: 30px;
    }
    
    .text-success { color: #28a745 !important; }
    .text-primary { color: #007bff !important; }
    .text-warning { color: #ffc107 !important; }
    .text-info { color: #17a2b8 !important; }
</style>
@endsection
