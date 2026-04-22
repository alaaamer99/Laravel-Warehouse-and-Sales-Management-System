@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">تفاصيل المورد</h5>
                    <div>
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> تعديل
                        </a>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">
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
                                        <td><strong>اسم المورد:</strong></td>
                                        <td>{{ $supplier->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>العنوان:</strong></td>
                                        <td>{{ $supplier->address }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>رقم الهاتف:</strong></td>
                                        <td>{{ $supplier->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>البريد الإلكتروني:</strong></td>
                                        <td>{{ $supplier->email ?: 'غير محدد' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>تاريخ الإضافة:</strong></td>
                                        <td>{{ $supplier->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>آخر تحديث:</strong></td>
                                        <td>{{ $supplier->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">إحصائيات المورد</h6>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">عدد المنتجات</h5>
                                            <h3 class="mb-0">{{ $supplier->products()->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">فواتير الشراء</h5>
                                            <h3 class="mb-0">{{ $supplier->purchaseInvoices()->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($supplier->products()->count() > 0)
                    <hr>
                    <h6 class="mb-3">منتجات المورد</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>اسم المنتج</th>
                                    <th>رقم المنتج</th>
                                    <th>الكمية المتاحة</th>
                                    <th>سعر البيع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplier->products()->take(10)->get() as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->product_number }}</td>
                                    <td>{{ $product->stock_quantity }}</td>
                                    <td>{{ number_format($product->selling_price, 2) }} ج.م</td>
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
