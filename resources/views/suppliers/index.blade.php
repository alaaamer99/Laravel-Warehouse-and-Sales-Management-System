@extends('layouts.app')

@section('title', 'الموردين - شركة بهجة')
@section('page-title', 'إدارة الموردين')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-truck me-2"></i>
                    قائمة الموردين
                </h4>
                <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    إضافة مورد جديد
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($suppliers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>الهاتف</th>
                                <th>البريد الإلكتروني</th>
                                <th>العنوان</th>
                                <th>الرصيد</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td>
                                    <strong>{{ $supplier->name }}</strong>
                                </td>
                                <td>
                                    <i class="fas fa-phone me-1"></i>
                                    {{ $supplier->phone }}
                                </td>
                                <td>
                                    @if($supplier->email)
                                        <i class="fas fa-envelope me-1"></i>
                                        {{ $supplier->email }}
                                    @else
                                        <span class="text-muted">غير محدد</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($supplier->address, 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $supplier->balance > 0 ? 'bg-danger' : 'bg-success' }}">
                                        {{ number_format($supplier->balance, 2) }} ج.م
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $supplier->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذا المورد؟')">
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
                    {{ $suppliers->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد موردين مضافين</h5>
                    <p class="text-muted">ابدأ بإضافة أول مورد لك</p>
                    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة مورد جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
