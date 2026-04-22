@extends('layouts.app')

@section('title', 'الأصناف - شركة بهجة')
@section('page-title', 'إدارة الأصناف')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-boxes me-2"></i>
                    قائمة الأصناف
                </h4>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    إضافة صنف جديد
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>اسم الصنف</th>
                                <th>المورد</th>
                                <th>المخزون</th>
                                <th>سعر الشراء</th>
                                <th>سعر الجملة</th>
                                <th>سعر التجزئة</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->barcode)
                                        <br><small class="text-muted">باركود: {{ $product->barcode }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $product->supplier->name }}</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $product->stock_cartons }}</strong> كرتونة
                                        <br>
                                        <strong>{{ $product->stock_units }}</strong> قطعة
                                        <br>
                                        <small class="text-muted">
                                            المجموع: {{ $product->total_stock_units }} قطعة
                                        </small>
                                    </div>
                                    @if($product->isLowStock())
                                        <span class="badge bg-warning">مخزون منخفض</span>
                                    @endif
                                </td>
                                <td>{{ number_format($product->purchase_price, 2) }} ج.م</td>
                                <td>{{ number_format($product->wholesale_price, 2) }} ج.م</td>
                                <td>{{ number_format($product->retail_price, 2) }} ج.م</td>
                                <td>
                                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
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
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد أصناف مضافة</h5>
                    <p class="text-muted">ابدأ بإضافة أول منتج لك</p>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة صنف جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
