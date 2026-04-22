@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">تفاصيل العميل</h5>
                    <div>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> تعديل
                        </a>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
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
                                        <td><strong>اسم العميل:</strong></td>
                                        <td>{{ $customer->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>رقم الهاتف:</strong></td>
                                        <td>{{ $customer->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>العنوان:</strong></td>
                                        <td>{{ $customer->address }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>تاريخ التسجيل:</strong></td>
                                        <td>{{ $customer->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>آخر تحديث:</strong></td>
                                        <td>{{ $customer->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">إحصائيات العميل</h6>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">عدد الفواتير</h6>
                                            <h3 class="mb-0">{{ $customer->salesInvoices()->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">إجمالي المشتريات</h6>
                                            <h3 class="mb-0">{{ number_format($customer->salesInvoices()->sum('total_amount'), 2) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">المدفوعات</h6>
                                            <h3 class="mb-0">{{ number_format($customer->payments()->sum('amount'), 2) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">المتبقي</h6>
                                            <h3 class="mb-0">{{ number_format($customer->salesInvoices()->sum('total_amount') - $customer->payments()->sum('amount'), 2) }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($customer->salesInvoices()->count() > 0)
                    <hr>
                    <h6 class="mb-3">آخر فواتير العميل</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الفاتورة</th>
                                    <th>التاريخ</th>
                                    <th>المندوب</th>
                                    <th>المبلغ الإجمالي</th>
                                    <th>حالة السداد</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->salesInvoices()->with('salesRepresentative')->latest()->take(5)->get() as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->invoice_date }}</td>
                                    <td>{{ $invoice->salesRepresentative->name }}</td>
                                    <td>{{ number_format($invoice->total_amount, 2) }} ج.م</td>
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
@endsection
