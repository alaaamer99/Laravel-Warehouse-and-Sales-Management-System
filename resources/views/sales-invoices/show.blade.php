@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">فاتورة مبيعات رقم: {{ $invoice->invoice_number }}</h5>
                    <div>
                        <a href="{{ route('sales-invoices.print', $invoice) }}" class="btn btn-info btn-sm" target="_blank">
                            <i class="fas fa-print me-1"></i> طباعة
                        </a>
                        <a href="{{ route('sales-invoices.edit', $invoice) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> تعديل
                        </a>
                        <button class="btn btn-info btn-sm" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> طباعة سريعة
                        </button>
                        <a href="{{ route('sales-invoices.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i> العودة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Invoice Header -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h3 class="text-primary">شركة بهجة للمنظفات</h3>
                            <p class="mb-1">العنوان: القاهرة - مصر</p>
                            <p class="mb-1">الهاتف: +20 123 456 789</p>
                            <p class="mb-0">البريد الإلكتروني: info@bahja.com</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <h4>فاتورة مبيعات</h4>
                            <table class="table table-sm table-borderless" style="width: auto; margin-left: auto;">
                                <tr>
                                    <td><strong>رقم الفاتورة:</strong></td>
                                    <td>{{ $invoice->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>التاريخ:</strong></td>
                                    <td>{{ $invoice->invoice_date }}</td>
                                </tr>
                                <tr>
                                    <td><strong>حالة السداد:</strong></td>
                                    <td>
                                        @if($invoice->payment_status == 'paid')
                                            <span class="badge bg-success">مدفوع</span>
                                        @elseif($invoice->payment_status == 'partial')
                                            <span class="badge bg-warning">جزئي</span>
                                        @else
                                            <span class="badge bg-danger">غير مدفوع</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Customer and Representative Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">بيانات العميل</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>الاسم:</strong> {{ $invoice->customer->name }}</p>
                                    <p class="mb-1"><strong>الهاتف:</strong> {{ $invoice->customer->phone }}</p>
                                    <p class="mb-0"><strong>العنوان:</strong> {{ $invoice->customer->address }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">مندوب المبيعات</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>الاسم:</strong> {{ $invoice->salesRepresentative->name }}</p>
                                    <p class="mb-0"><strong>الهاتف:</strong> {{ $invoice->salesRepresentative->phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="40%">المنتج</th>
                                    <th width="15%" class="text-center">كراتين</th>
                                    <th width="10%" class="text-center">قطع</th>
                                    <th width="15%" class="text-center">سعر الوحدة</th>
                                    <th width="15%" class="text-center">الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->product->name }}</strong><br>
                                        <small class="text-muted">كود المنتج: {{ $item->product->id }}</small>
                                    </td>
                                    <td class="text-center">{{ $item->quantity_cartons }}</td>
                                    <td class="text-center">{{ $item->quantity_units }}</td>
                                    <td class="text-center">{{ number_format($item->unit_price, 2) }} ج.م</td>
                                    <td class="text-center">{{ number_format($item->total_price, 2) }} ج.م</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Invoice Totals -->
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td>المجموع الفرعي:</td>
                                            <td class="text-end">{{ number_format($invoice->subtotal_amount, 2) }} ج.م</td>
                                        </tr>
                                        <tr>
                                            <td>الضريبة:</td>
                                            <td class="text-end">{{ number_format($invoice->tax_amount, 2) }} ج.م</td>
                                        </tr>
                                        <tr>
                                            <td>الخصم:</td>
                                            <td class="text-end">{{ number_format($invoice->discount_amount, 2) }} ج.م</td>
                                        </tr>
                                        <tr class="table-dark">
                                            <td><strong>الإجمالي النهائي:</strong></td>
                                            <td class="text-end"><strong>{{ number_format($invoice->total_amount, 2) }} ج.م</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            @if($invoice->notes)
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">الملاحظات</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $invoice->notes }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">ملخص المدفوعات</h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $totalPaid = $invoice->payments->sum('amount');
                                        $remaining = $invoice->total_amount - $totalPaid;
                                    @endphp
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td>إجمالي الفاتورة:</td>
                                            <td class="text-end">{{ number_format($invoice->total_amount, 2) }} ج.م</td>
                                        </tr>
                                        <tr>
                                            <td>المدفوع:</td>
                                            <td class="text-end">{{ number_format($totalPaid, 2) }} ج.م</td>
                                        </tr>
                                        <tr class="{{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                                            <td><strong>المتبقي:</strong></td>
                                            <td class="text-end"><strong>{{ number_format($remaining, 2) }} ج.م</strong></td>
                                        </tr>
                                    </table>
                                    @if($remaining > 0)
                                    <div class="mt-2">
                                        <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-plus me-1"></i> إضافة دفعة
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payments History -->
                    @if($invoice->payments->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>تاريخ المدفوعات</h6>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>طريقة الدفع</th>
                                            <th>الملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoice->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_date }}</td>
                                            <td>{{ number_format($payment->amount, 2) }} ج.م</td>
                                            <td>{{ $payment->payment_method }}</td>
                                            <td>{{ $payment->notes ?: '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
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
}
</style>
@endsection
