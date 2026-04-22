@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h2 mb-0">
                        <i class="fas fa-money-bill-wave me-3"></i>
                        المدفوعات
                    </h1>
                    <p class="mb-0 mt-2">إدارة ومتابعة جميع المدفوعات والتحصيلات</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('payments.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>تسجيل دفعة جديدة
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
                                <i class="fas fa-coins stats-icon"></i>
                                <h5 class="mt-3">إجمالي المدفوعات</h5>
                                <h4 class="mb-0">{{ number_format($payments->sum('amount'), 2) }} ج.م</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow">
                        <div class="card-body text-center">
                            <div class="stats-card bg-success">
                                <i class="fas fa-money-bill stats-icon"></i>
                                <h5 class="mt-3">مدفوعات نقدية</h5>
                                <h4 class="mb-0">{{ number_format($payments->where('payment_method', 'cash')->sum('amount'), 2) }} ج.م</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow">
                        <div class="card-body text-center">
                            <div class="stats-card bg-warning">
                                <i class="fas fa-check stats-icon"></i>
                                <h5 class="mt-3">مدفوعات شيكات</h5>
                                <h4 class="mb-0">{{ number_format($payments->where('payment_method', 'check')->sum('amount'), 2) }} ج.م</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow">
                        <div class="card-body text-center">
                            <div class="stats-card bg-info">
                                <i class="fas fa-university stats-icon"></i>
                                <h5 class="mt-3">تحويلات بنكية</h5>
                                <h4 class="mb-0">{{ number_format($payments->where('payment_method', 'bank_transfer')->sum('amount'), 2) }} ج.م</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>
                        قائمة المدفوعات
                    </h5>
                </div>
                <div class="card-body">
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>رقم الدفعة</th>
                                        <th>رقم الفاتورة</th>
                                        <th>العميل</th>
                                        <th>مندوب المبيعات</th>
                                        <th>المبلغ</th>
                                        <th>طريقة الدفع</th>
                                        <th>تاريخ الدفع</th>
                                        <th>رقم المرجع</th>
                                        <th>العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <strong>#{{ $payment->id }}</strong>
                                            </td>
                                            <td>{{ $payment->salesInvoice->invoice_number }}</td>
                                            <td>{{ $payment->salesInvoice->customer->name }}</td>
                                            <td>{{ $payment->salesInvoice->salesRepresentative->name }}</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    {{ number_format($payment->amount, 2) }} ج.م
                                                </span>
                                            </td>
                                            <td>
                                                @if($payment->payment_method == 'cash')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-money-bill me-1"></i>نقدي
                                                    </span>
                                                @elseif($payment->payment_method == 'check')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-check me-1"></i>شيك
                                                    </span>
                                                @else
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-university me-1"></i>تحويل بنكي
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                            <td>
                                                {{ $payment->reference_number ?? '-' }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('payments.show', $payment->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @can('update', $payment)
                                                        <a href="{{ route('payments.edit', $payment->id) }}" 
                                                           class="btn btn-sm btn-outline-secondary" title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    
                                                    @can('delete', $payment)
                                                        <form method="POST" action="{{ route('payments.destroy', $payment->id) }}" 
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
                            {{ $payments->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد مدفوعات حتى الآن</h5>
                            <p class="text-muted">يمكنك تسجيل دفعة جديدة من خلال النقر على الزر أعلاه</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
