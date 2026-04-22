@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h2 mb-0">
                        <i class="fas fa-tachometer-alt me-3"></i>
                        مرحباً، {{ Auth::user()->name }}
                    </h1>
                    <p class="mb-0 mt-2">
                        @if(auth()->user()->role === 'super_admin')
                            مدير النظام العام - لديك صلاحيات كاملة على النظام
                        @elseif(auth()->user()->role === 'admin')
                            مدير النظام - يمكنك إدارة جميع العمليات والتقارير
                        @else
                            مندوب مبيعات - يمكنك إدارة فواتير البيع والمدفوعات الخاصة بك
                        @endif
                    </p>
                </div>
                <div class="col-auto">
                    <span class="badge bg-light text-dark fs-6">
                        <i class="fas fa-calendar me-2"></i>
                        {{ now()->format('Y-m-d') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                @if(auth()->user()->role === 'sales_representative')
                    <!-- Sales Representative Stats -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <div class="stats-card bg-primary">
                                    <i class="fas fa-chart-line stats-icon"></i>
                                    <h5 class="mt-3">مبيعاتي الشهرية</h5>
                                    <h4 class="mb-0">{{ number_format($stats['monthly_sales'] ?? 0, 2) }} ج.م</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <div class="stats-card bg-success">
                                    <i class="fas fa-coins stats-icon"></i>
                                    <h5 class="mt-3">تحصيلاتي الشهرية</h5>
                                    <h4 class="mb-0">{{ number_format($stats['monthly_collections'] ?? 0, 2) }} ج.م</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <div class="stats-card bg-warning">
                                    <i class="fas fa-exclamation-triangle stats-icon"></i>
                                    <h5 class="mt-3">فواتير معلقة</h5>
                                    <h4 class="mb-0">{{ $stats['pending_invoices'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <div class="stats-card bg-info">
                                    <i class="fas fa-users stats-icon"></i>
                                    <h5 class="mt-3">إجمالي العملاء</h5>
                                    <h4 class="mb-0">{{ $stats['total_customers'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Admin Stats -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <div class="stats-card bg-primary">
                                    <i class="fas fa-chart-line stats-icon"></i>
                                    <h5 class="mt-3">مبيعات الشهر</h5>
                                    <h4 class="mb-0">{{ number_format($stats['monthly_sales'] ?? 0, 2) }} ج.م</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <div class="stats-card bg-success">
                                    <i class="fas fa-shopping-cart stats-icon"></i>
                                    <h5 class="mt-3">مشتريات الشهر</h5>
                                    <h4 class="mb-0">{{ number_format($stats['monthly_purchases'] ?? 0, 2) }} ج.م</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <div class="stats-card bg-info">
                                    <i class="fas fa-coins stats-icon"></i>
                                    <h5 class="mt-3">تحصيلات الشهر</h5>
                                    <h4 class="mb-0">{{ number_format($stats['monthly_collections'] ?? 0, 2) }} ج.م</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <div class="stats-card bg-warning">
                                    <i class="fas fa-exclamation-triangle stats-icon"></i>
                                    <h5 class="mt-3">منتجات قليلة المخزون</h5>
                                    <h4 class="mb-0">{{ $stats['low_stock_products'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Admin Stats -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <div class="stats-card bg-secondary">
                                    <i class="fas fa-users stats-icon"></i>
                                    <h5 class="mt-3">العملاء</h5>
                                    <h4 class="mb-0">{{ $stats['total_customers'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <div class="stats-card bg-dark">
                                    <i class="fas fa-truck stats-icon"></i>
                                    <h5 class="mt-3">الموردين</h5>
                                    <h4 class="mb-0">{{ $stats['total_suppliers'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <div class="stats-card bg-purple">
                                    <i class="fas fa-boxes stats-icon"></i>
                                    <h5 class="mt-3">الأصناف</h5>
                                    <h4 class="mb-0">{{ $stats['total_products'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <div class="stats-card bg-cyan">
                                    <i class="fas fa-user-tie stats-icon"></i>
                                    <h5 class="mt-3">مندوبي المبيعات</h5>
                                    <h4 class="mb-0">{{ $stats['total_representatives'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                <!-- Recent Invoices -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-receipt me-2"></i>
                                أحدث الفواتير
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($recentInvoices && $recentInvoices->count() > 0)
                                @foreach($recentInvoices as $invoice)
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                        <div>
                                            <strong>{{ $invoice->invoice_number }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $invoice->customer->name }}</small>
                                            @if(auth()->user()->role !== 'sales_representative')
                                                <br>
                                                <small class="text-muted">{{ $invoice->salesRepresentative->name }}</small>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary">{{ number_format($invoice->total_amount, 2) }} ج.م</span>
                                            <br>
                                            <small class="text-muted">{{ $invoice->invoice_date->format('Y-m-d') }}</small>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="text-center mt-3">
                                    <a href="{{ route('sales-invoices.index') }}" class="btn btn-outline-primary btn-sm">
                                        عرض جميع الفواتير
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">لا توجد فواتير حديثة</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                أحدث المدفوعات
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($recentPayments && $recentPayments->count() > 0)
                                @foreach($recentPayments as $payment)
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                        <div>
                                            <strong>{{ $payment->salesInvoice->invoice_number }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $payment->salesInvoice->customer->name }}</small>
                                            @if(auth()->user()->role !== 'sales_representative')
                                                <br>
                                                <small class="text-muted">{{ $payment->salesInvoice->salesRepresentative->name }}</small>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-success">{{ number_format($payment->amount, 2) }} ج.م</span>
                                            <br>
                                            <small class="text-muted">{{ $payment->payment_date->format('Y-m-d') }}</small>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="text-center mt-3">
                                    <a href="{{ route('payments.index') }}" class="btn btn-outline-success btn-sm">
                                        عرض جميع المدفوعات
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">لا توجد مدفوعات حديثة</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role !== 'sales_representative' && isset($lowStockProducts))
                <!-- Low Stock Products -->
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                منتجات قليلة المخزون
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($lowStockProducts && $lowStockProducts->count() > 0)
                                <div class="row">
                                    @foreach($lowStockProducts as $product)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="alert alert-warning mb-0">
                                                <strong>{{ $product->name }}</strong>
                                                <br>
                                                <small>المورد: {{ $product->supplier->name }}</small>
                                                <br>
                                                <span class="badge bg-danger">الكمية: {{ $product->total_stock_units }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('reports.inventory') }}" class="btn btn-outline-warning btn-sm">
                                        عرض تقرير المخزون
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <p class="text-success mb-0">جميع المنتجات لديها مخزون كافي</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-bolt me-2"></i>
                                إجراءات سريعة
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('sales-invoices.create') }}" class="btn btn-primary w-100">
                                        <i class="fas fa-plus me-2"></i>
                                        إضافة فاتورة بيع
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('payments.create') }}" class="btn btn-success w-100">
                                        <i class="fas fa-money-bill me-2"></i>
                                        تسجيل دفعة
                                    </a>
                                </div>
                                @if(auth()->user()->role !== 'sales_representative')
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('products.create') }}" class="btn btn-info w-100">
                                        <i class="fas fa-box me-2"></i>
                                        إضافة منتج
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('customers.create') }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-user-plus me-2"></i>
                                        إضافة عميل
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white;
    }
    .bg-cyan {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        color: white;
    }
    .stats-card {
        padding: 2rem;
        border-radius: 0.75rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }
</style>
@endpush
