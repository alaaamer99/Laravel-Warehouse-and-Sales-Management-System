@extends('layouts.app')

@section('title', 'فواتير الشراء - شركة بهجة')
@section('page-title', 'فواتير الشراء')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-shopping-cart me-2"></i>
            فواتير الشراء
        </h1>
        <a href="{{ route('purchase-invoices.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            إضافة فاتورة شراء جديدة
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($invoices->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>المورد</th>
                                <th>تاريخ الفاتورة</th>
                                <th>عدد الأصناف</th>
                                <th>المبلغ الإجمالي</th>
                                <th>حالة الدفع</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $invoice->invoice_number }}</strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-truck me-1"></i>
                                        {{ $invoice->supplier->name }}
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $invoice->invoice_date->format('Y-m-d') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $invoice->items->count() }} صنف</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($invoice->total_amount, 2) }} ج.م</strong>
                                    </td>
                                    <td>
                                        @if($invoice->payment_status === 'paid')
                                            <span class="badge bg-success">مدفوعة</span>
                                        @elseif($invoice->payment_status === 'partial')
                                            <span class="badge bg-warning">مدفوعة جزئياً</span>
                                        @else
                                            <span class="badge bg-danger">غير مدفوعة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $invoice->created_at->format('Y-m-d H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('purchase-invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('purchase-invoices.edit', $invoice) }}" class="btn btn-sm btn-outline-warning" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('purchase-invoices.destroy', $invoice) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger btn-delete" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $invoices->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد فواتير شراء</h5>
                    <p class="text-muted">لم يتم إنشاء أي فواتير شراء بعد</p>
                    <a href="{{ route('purchase-invoices.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة أول فاتورة شراء
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">إجمالي الفواتير</h6>
                            <h4 class="mb-0">{{ $invoices->total() }}</h4>
                        </div>
                        <div>
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">المبلغ الإجمالي</h6>
                            <h4 class="mb-0">{{ number_format($invoices->sum('total_amount'), 2) }} ج.م</h4>
                        </div>
                        <div>
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">فواتير معلقة</h6>
                            <h4 class="mb-0">{{ $invoices->where('payment_status', 'pending')->count() }}</h4>
                        </div>
                        <div>
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">فواتير هذا الشهر</h6>
                            <h4 class="mb-0">{{ $invoices->where('invoice_date', '>=', now()->startOfMonth())->count() }}</h4>
                        </div>
                        <div>
                            <i class="fas fa-calendar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
