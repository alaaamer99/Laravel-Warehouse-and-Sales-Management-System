@extends('layouts.app')

@section('title', 'فواتير البيع - شركة بهجة')
@section('page-title', 'فواتير البيع')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="h2 mb-0">
                            <i class="fas fa-receipt me-3"></i>
                            فواتير البيع
                        </h1>
                        <p class="mb-0 mt-2">إدارة ومتابعة جميع فواتير البيع</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('sales-invoices.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>إضافة فاتورة جديدة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow">
                        <div class="card-body text-center">
                            <div class="stats-card bg-primary">
                                <i class="fas fa-file-invoice stats-icon"></i>
                                <h5 class="mt-3">إجمالي الفواتير</h5>
                                <h4 class="mb-0">{{ $invoices->total() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow">
                        <div class="card-body text-center">
                            <div class="stats-card bg-success">
                                <i class="fas fa-check-circle stats-icon"></i>
                                <h5 class="mt-3">فواتير مدفوعة</h5>
                                <h4 class="mb-0">{{ $invoices->where('payment_status', 'paid')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow">
                        <div class="card-body text-center">
                            <div class="stats-card bg-warning">
                                <i class="fas fa-exclamation-triangle stats-icon"></i>
                                <h5 class="mt-3">فواتير جزئية</h5>
                                <h4 class="mb-0">{{ $invoices->where('payment_status', 'partial')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow">
                        <div class="card-body text-center">
                            <div class="stats-card bg-danger">
                                <i class="fas fa-times-circle stats-icon"></i>
                                <h5 class="mt-3">فواتير معلقة</h5>
                                <h4 class="mb-0">{{ $invoices->where('payment_status', 'pending')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoices Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>
                        قائمة الفواتير
                    </h5>
                </div>
                <div class="card-body">
                    @if($invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>رقم الفاتورة</th>
                                        <th>العميل</th>
                                        <th>مندوب المبيعات</th>
                                        <th>تاريخ الفاتورة</th>
                                        <th>إجمالي المبلغ</th>
                                        <th>المبلغ المدفوع</th>
                                        <th>المبلغ المتبقي</th>
                                        <th>الحالة</th>
                                        <th>العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <strong>{{ $invoice->invoice_number }}</strong>
                                            </td>
                                            <td>{{ $invoice->customer->name }}</td>
                                            <td>{{ $invoice->salesRepresentative->name }}</td>
                                            <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                            <td>                                            <span class="badge bg-primary">
                                                    {{ number_format($invoice->total_amount, 2) }} ج.م
                                                </span>
                                            </td>
                                            <td>
                                            <span class="badge bg-success">
                                                    {{ number_format($invoice->paid_amount, 2) }} ج.م
                                                </span>
                                            </td>
                                            <td>
                                            <span class="badge bg-warning">
                                                    {{ number_format($invoice->remaining_amount, 2) }} ج.م
                                                </span>
                                            </td>
                                            <td>
                                                @if($invoice->payment_status == 'paid')
                                                    <span class="badge bg-success">مدفوعة</span>
                                                @elseif($invoice->payment_status == 'partial')
                                                    <span class="badge bg-warning">مدفوعة جزئياً</span>
                                                @else
                                                    <span class="badge bg-danger">غير مدفوعة</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('sales-invoices.show', $invoice->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @can('update', $invoice)
                                                        <a href="{{ route('sales-invoices.edit', $invoice->id) }}" 
                                                           class="btn btn-sm btn-outline-secondary" title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    
                                                    @can('delete', $invoice)
                                                        <form method="POST" action="{{ route('sales-invoices.destroy', $invoice->id) }}" 
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-delete" title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $invoices->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد فواتير بيع حتى الآن</h5>
                            <p class="text-muted">يمكنك إضافة فاتورة جديدة من خلال النقر على الزر أعلاه</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
