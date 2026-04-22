@extends('layouts.app')

@section('title', 'تفاصيل فاتورة الشراء - شركة بهجة')
@section('page-title', 'تفاصيل فاتورة الشراء')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-receipt me-2"></i>
            تفاصيل فاتورة الشراء {{ $invoice->invoice_number }}
        </h1>
        <div>
            <a href="{{ route('purchase-invoices.print', $invoice) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-print me-2"></i>
                طباعة
            </a>
            <a href="{{ route('purchase-invoices.edit', $invoice) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>
                تعديل
            </a>
            <a href="{{ route('purchase-invoices.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Invoice Header -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الفاتورة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>رقم الفاتورة:</strong></td>
                                    <td>{{ $invoice->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>المورد:</strong></td>
                                    <td>
                                        <i class="fas fa-truck me-1"></i>
                                        {{ $invoice->supplier->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>تاريخ الفاتورة:</strong></td>
                                    <td>
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $invoice->invoice_date->format('Y-m-d') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>حالة الدفع:</strong></td>
                                    <td>
                                        @if($invoice->payment_status === 'paid')
                                            <span class="badge bg-success">مدفوعة</span>
                                        @elseif($invoice->payment_status === 'partial')
                                            <span class="badge bg-warning">مدفوعة جزئياً</span>
                                        @else
                                            <span class="badge bg-danger">غير مدفوعة</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>تاريخ الإنشاء:</strong></td>
                                    <td>{{ $invoice->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>آخر تحديث:</strong></td>
                                    <td>{{ $invoice->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($invoice->notes)
                        <div class="mt-3">
                            <strong>الملاحظات:</strong>
                            <p class="mt-2 p-3 bg-light rounded">{{ $invoice->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Invoice Summary -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        ملخص الفاتورة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="p-3 bg-primary text-white rounded">
                                <h6 class="mb-1">عدد الأصناف</h6>
                                <h4 class="mb-0">{{ $invoice->items->count() }}</h4>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="p-3 bg-success text-white rounded">
                                <h6 class="mb-1">المبلغ الإجمالي</h6>
                                <h4 class="mb-0">{{ number_format($invoice->total_amount, 2) }} ج.م</h4>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-info text-white rounded">
                                <h6 class="mb-1">إجمالي الكمية</h6>
                                <h4 class="mb-0">
                                    @php
                                        $totalQuantity = $invoice->items->sum(function($item) {
                                            return ($item->quantity_cartons * $item->product->units_per_carton) + $item->quantity_units;
                                        });
                                    @endphp
                                    {{ $totalQuantity }} قطعة
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Items -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                أصناف الفاتورة
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>المنتج</th>
                            <th>المورد</th>
                            <th>الكمية (كرتونة)</th>
                            <th>الكمية (قطعة)</th>
                            <th>إجمالي الكمية</th>
                            <th>سعر الوحدة</th>
                            <th>المبلغ الفرعي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $item->product->name }}</strong>
                                    @if($item->product->product_number)
                                        <br><small class="text-muted">{{ $item->product->product_number }}</small>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-truck me-1"></i>
                                    {{ $item->product->supplier->name }}
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $item->quantity_cartons }} كرتونة</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item->quantity_units }} قطعة</span>
                                </td>
                                <td>
                                    @php
                                        $totalUnits = ($item->quantity_cartons * $item->product->units_per_carton) + $item->quantity_units;
                                    @endphp
                                    <strong>{{ $totalUnits }} قطعة</strong>
                                </td>
                                <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                                <td>
                                    <strong class="text-success">{{ number_format($item->total_price, 2) }} ج.م</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-success">
                            <th colspan="7">المجموع الإجمالي</th>
                            <th>{{ number_format($invoice->total_amount, 2) }} ج.م</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('purchase-invoices.edit', $invoice) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>
                    تعديل الفاتورة
                </a>
                <button type="button" class="btn btn-info" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>
                    طباعة
                </button>
                <form action="{{ route('purchase-invoices.destroy', $invoice) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-delete">
                        <i class="fas fa-trash me-2"></i>
                        حذف الفاتورة
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .btn, .card-header, .d-flex.justify-content-between {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush
@endsection
