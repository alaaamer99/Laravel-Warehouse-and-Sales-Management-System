@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">تفاصيل الدفعة</h5>
                    <div>
                        <a href="{{ route('payments.print', $payment) }}" class="btn btn-info btn-sm" target="_blank">
                            <i class="fas fa-print me-1"></i> طباعة إيصال
                        </a>
                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> تعديل
                        </a>
                        <button class="btn btn-info btn-sm" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> طباعة سريعة
                        </button>
                        <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i> العودة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Payment Header -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h3 class="text-primary">شركة بهجة للمنظفات</h3>
                            <p class="mb-1">العنوان: القاهرة - مصر</p>
                            <p class="mb-1">الهاتف: +20 123 456 789</p>
                            <p class="mb-0">البريد الإلكتروني: info@bahja.com</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <h4>إيصال دفع</h4>
                            <table class="table table-sm table-borderless" style="width: auto; margin-left: auto;">
                                <tr>
                                    <td><strong>رقم الإيصال:</strong></td>
                                    <td>{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>تاريخ الدفع:</strong></td>
                                    <td>{{ $payment->payment_date }}</td>
                                </tr>
                                <tr>
                                    <td><strong>طريقة الدفع:</strong></td>
                                    <td>
                                        @switch($payment->payment_method)
                                            @case('cash') نقداً @break
                                            @case('bank_transfer') تحويل بنكي @break
                                            @case('check') شيك @break
                                            @case('credit_card') بطاقة ائتمان @break
                                            @default {{ $payment->payment_method }}
                                        @endswitch
                                    </td>
                                </tr>
                                @if($payment->reference_number)
                                <tr>
                                    <td><strong>رقم المرجع:</strong></td>
                                    <td>{{ $payment->reference_number }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Invoice and Customer Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">بيانات العميل</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>الاسم:</strong> {{ $payment->salesInvoice->customer->name }}</p>
                                    <p class="mb-1"><strong>الهاتف:</strong> {{ $payment->salesInvoice->customer->phone }}</p>
                                    <p class="mb-0"><strong>العنوان:</strong> {{ $payment->salesInvoice->customer->address }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">بيانات الفاتورة</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>رقم الفاتورة:</strong> 
                                        <a href="{{ route('sales-invoices.show', $payment->salesInvoice) }}">
                                            {{ $payment->salesInvoice->invoice_number }}
                                        </a>
                                    </p>
                                    <p class="mb-1"><strong>تاريخ الفاتورة:</strong> {{ $payment->salesInvoice->invoice_date }}</p>
                                    <p class="mb-0"><strong>مندوب المبيعات:</strong> {{ $payment->salesInvoice->salesRepresentative->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Amount -->
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white text-center">
                                    <h5 class="mb-0">مبلغ الدفعة</h5>
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="display-4 text-primary mb-3">{{ number_format($payment->amount, 2) }} ج.م</h2>
                                    <p class="mb-0">
                                        <span class="badge bg-secondary">{{ $payment->amount_in_words ?? 'مبلغ وقدره ' . number_format($payment->amount, 2) . ' ج.م' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Summary -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            @if($payment->notes)
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">الملاحظات</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $payment->notes }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">ملخص الفاتورة</h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $totalPaid = $payment->salesInvoice->payments->sum('amount');
                                        $remaining = $payment->salesInvoice->total_amount - $totalPaid;
                                    @endphp
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td>إجمالي الفاتورة:</td>
                                            <td class="text-end">{{ number_format($payment->salesInvoice->total_amount, 2) }} ج.م</td>
                                        </tr>
                                        <tr>
                                            <td>إجمالي المدفوع:</td>
                                            <td class="text-end">{{ number_format($totalPaid, 2) }} ج.م</td>
                                        </tr>
                                        <tr class="{{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                                            <td><strong>المتبقي:</strong></td>
                                            <td class="text-end"><strong>{{ number_format($remaining, 2) }} ج.م</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-center pt-2">
                                                @if($remaining <= 0)
                                                    <span class="badge bg-success">تم السداد بالكامل</span>
                                                @elseif($totalPaid > 0)
                                                    <span class="badge bg-warning">سداد جزئي</span>
                                                @else
                                                    <span class="badge bg-danger">غير مدفوع</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History -->
                    @if($payment->salesInvoice->payments->count() > 1)
                    <div class="row">
                        <div class="col-12">
                            <h6>تاريخ جميع المدفوعات لهذه الفاتورة</h6>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>طريقة الدفع</th>
                                            <th>رقم المرجع</th>
                                            <th>الملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payment->salesInvoice->payments->sortBy('payment_date') as $p)
                                        <tr class="{{ $p->id === $payment->id ? 'table-warning' : '' }}">
                                            <td>
                                                {{ $p->payment_date }}
                                                @if($p->id === $payment->id)
                                                    <span class="badge bg-warning ms-1">هذه الدفعة</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($p->amount, 2) }} ج.م</td>
                                            <td>
                                                @switch($p->payment_method)
                                                    @case('cash') نقداً @break
                                                    @case('bank_transfer') تحويل بنكي @break
                                                    @case('check') شيك @break
                                                    @case('credit_card') بطاقة ائتمان @break
                                                    @default {{ $p->payment_method }}
                                                @endswitch
                                            </td>
                                            <td>{{ $p->reference_number ?: '-' }}</td>
                                            <td>{{ $p->notes ?: '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Footer -->
                    <div class="row mt-5">
                        <div class="col-12 text-center">
                            <hr>
                            <p class="text-muted mb-0">شكراً لتعاملكم معنا</p>
                            <small class="text-muted">تم إنشاء هذا الإيصال في {{ $payment->created_at->format('Y-m-d H:i:s') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header .d-flex, .no-print {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-body {
        padding: 0 !important;
    }
    
    .table {
        font-size: 12px;
    }
    
    .container-fluid {
        padding: 0 !important;
    }
    
    body {
        font-size: 14px;
    }
}
</style>
@endsection
