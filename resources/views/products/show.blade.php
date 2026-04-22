@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">تفاصيل المنتج</h5>
                    <div>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> تعديل
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
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
                                        <td><strong>اسم المنتج:</strong></td>
                                        <td>{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>رقم المنتج:</strong></td>
                                        <td>{{ $product->product_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>المورد:</strong></td>
                                        <td>{{ $product->supplier->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>سعر الشراء:</strong></td>
                                        <td>{{ number_format($product->purchase_price, 2) }} ج.م</td>
                                    </tr>
                                    <tr>
                                        <td><strong>سعر الجملة:</strong></td>
                                        <td>{{ number_format($product->wholesale_price, 2) }} ج.م</td>
                                    </tr>
                                    <tr>
                                        <td><strong>سعر التجزئة:</strong></td>
                                        <td>{{ number_format($product->retail_price, 2) }} ج.م</td>
                                    </tr>
                                    <tr>
                                        <td><strong>هامش الربح (جملة):</strong></td>
                                        <td>{{ number_format((($product->wholesale_price - $product->purchase_price) / $product->purchase_price) * 100, 2) }}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><strong>عدد القطع في الكرتونة:</strong></td>
                                        <td>{{ $product->units_per_carton }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>عدد الكراتين:</strong></td>
                                        <td>{{ $product->stock_cartons }} كرتونة</td>
                                    </tr>
                                    <tr>
                                        <td><strong>القطع المتبقية:</strong></td>
                                        <td>{{ $product->stock_units }} قطعة</td>
                                    </tr>
                                    <tr>
                                        <td><strong>إجمالي الكمية:</strong></td>
                                        <td>{{ $product->total_stock_units }} قطعة</td>
                                    </tr>
                                    <tr>
                                        <td><strong>الباركود:</strong></td>
                                        <td>{{ $product->barcode ?: 'غير محدد' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>حالة المخزون:</strong></td>
                                        <td>
                                            @if($product->isLowStock())
                                                <span class="badge bg-danger">منخفض</span>
                                            @elseif($product->total_stock_units <= 50)
                                                <span class="badge bg-warning">متوسط</span>
                                            @else
                                                <span class="badge bg-success">متوفر</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3">إحصائيات المنتج</h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">مرات البيع</h6>
                                            <h4 class="mb-0">{{ $product->salesInvoiceItems()->count() }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">إجمالي المبيعات</h6>
                                        <h4 class="mb-0">
                                            {{ $product->salesInvoiceItems->sum(function($item) use ($product) {
                                                return ($item->quantity_cartons * $product->units_per_carton) + $item->quantity_units;
                                            }) }}
                                        </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">قيمة المخزون</h6>
                                            <h4 class="mb-0">
                                                {{ number_format((($product->stock_cartons * $product->units_per_carton) + $product->stock_units) * $product->purchase_price, 0) }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    @if($product->salesInvoiceItems()->count() > 0)
                    <hr>
                    <h6 class="mb-3">آخر مبيعات المنتج</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الفاتورة</th>
                                    <th>العميل</th>
                                    <th>الكمية</th>
                                    <th>السعر</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->salesInvoiceItems()->with('salesInvoice.customer')->latest()->take(5)->get() as $item)
                                <tr>
                                    <td>{{ $item->salesInvoice->invoice_number }}</td>
                                    <td>{{ $item->salesInvoice->customer->name }}</td>
                                    <td>
                                        {{ $item->quantity_cartons > 0 ? $item->quantity_cartons . ' كرتونة' : '' }}
                                        {{ $item->quantity_units > 0 ? $item->quantity_units . ' قطعة' : '' }}
                                    </td>
                                    <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                                    <td>{{ $item->salesInvoice->invoice_date }}</td>
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
