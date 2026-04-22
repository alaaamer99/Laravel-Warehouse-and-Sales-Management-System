@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">تفاصيل مندوب المبيعات</h5>
                    <div>
                        <a href="{{ route('sales-representatives.edit', $salesRepresentative) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> تعديل
                        </a>
                        <a href="{{ route('sales-representatives.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i> العودة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><strong>اسم المندوب:</strong></td>
                                        <td>{{ $salesRepresentative->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>رقم الهاتف:</strong></td>
                                        <td>{{ $salesRepresentative->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>العنوان:</strong></td>
                                        <td>{{ $salesRepresentative->address }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>المستخدم المرتبط:</strong></td>
                                        <td>{{ $salesRepresentative->user ? $salesRepresentative->user->email : 'غير مرتبط' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>تاريخ التسجيل:</strong></td>
                                        <td>{{ $salesRepresentative->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>آخر تحديث:</strong></td>
                                        <td>{{ $salesRepresentative->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">إحصائيات المندوب</h6>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">عدد الفواتير</h6>
                                            <h3 class="mb-0">{{ $salesRepresentative->salesInvoices()->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">إجمالي المبيعات</h6>
                                            <h3 class="mb-0">{{ number_format($salesRepresentative->salesInvoices()->sum('total_amount'), 2) }} ج.م</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">عدد العملاء</h6>
                                            <h3 class="mb-0">{{ $salesRepresentative->salesInvoices()->distinct('customer_id')->count('customer_id') }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">السحوبات</h6>
                                            <h3 class="mb-0">{{ $salesRepresentative->withdrawals()->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            @if($salesRepresentative->salesInvoices()->count() > 0)
                            <h6 class="mb-3">آخر فواتير المندوب</h6>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>رقم الفاتورة</th>
                                            <th>العميل</th>
                                            <th>المبلغ</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($salesRepresentative->salesInvoices()->with('customer')->latest()->take(5)->get() as $invoice)
                                        <tr>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->customer->name }}</td>
                                            <td>{{ number_format($invoice->total_amount, 2) }} ج.م</td>
                                            <td>{{ $invoice->invoice_date }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($salesRepresentative->withdrawals()->count() > 0)
                            <h6 class="mb-3">آخر السحوبات</h6>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>المنتج</th>
                                            <th>الكمية</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($salesRepresentative->withdrawals()->with('product')->latest()->take(5)->get() as $withdrawal)
                                        <tr>
                                            <td>{{ $withdrawal->product->name }}</td>
                                            <td>{{ $withdrawal->quantity }}</td>
                                            <td>{{ $withdrawal->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
