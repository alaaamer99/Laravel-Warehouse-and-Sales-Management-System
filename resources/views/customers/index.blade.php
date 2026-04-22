@extends('layouts.app')

@section('title', 'العملاء - شركة بهجة')
@section('page-title', 'إدارة العملاء')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    قائمة العملاء
                </h4>
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    إضافة عميل جديد
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>الهاتف</th>
                                <th>البريد الإلكتروني</th>
                                <th>العنوان</th>
                                <th>نوع السعر</th>
                                <th>الرصيد</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr>
                                <td>
                                    <strong>{{ $customer->name }}</strong>
                                </td>
                                <td>
                                    <i class="fas fa-phone me-1"></i>
                                    {{ $customer->phone }}
                                </td>
                                <td>
                                    @if($customer->email)
                                        <i class="fas fa-envelope me-1"></i>
                                        {{ $customer->email }}
                                    @else
                                        <span class="text-muted">غير محدد</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($customer->address, 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $customer->price_type == 'wholesale' ? 'bg-primary' : 'bg-success' }}">
                                        {{ $customer->price_type == 'wholesale' ? 'جملة' : 'تجزئة' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $customer->balance > 0 ? 'bg-danger' : 'bg-success' }}">
                                        {{ number_format($customer->balance, 2) }} ج.م
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $customer->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذا العميل؟')">
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
                    {{ $customers->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد عملاء مضافين</h5>
                    <p class="text-muted">ابدأ بإضافة أول عميل لك</p>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة عميل جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
