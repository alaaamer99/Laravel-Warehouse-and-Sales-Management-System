@extends('layouts.app')

@section('title', 'مندوبي المبيعات - شركة بهجة')
@section('page-title', 'إدارة مندوبي المبيعات')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-user-tie me-2"></i>
                    قائمة مندوبي المبيعات
                </h4>
                <a href="{{ route('sales-representatives.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    إضافة مندوب جديد
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($representatives->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الهاتف</th>
                                <th>العنوان</th>
                                <th>الرصيد</th>
                                <th>الحالة</th>
                                <th>تاريخ التسجيل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($representatives as $rep)
                            <tr>
                                <td>
                                    <strong>{{ $rep->name }}</strong>
                                </td>
                                <td>
                                    <i class="fas fa-envelope me-1"></i>
                                    {{ $rep->user->email }}
                                </td>
                                <td>
                                    <i class="fas fa-phone me-1"></i>
                                    {{ $rep->phone }}
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($rep->address, 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $rep->balance > 0 ? 'bg-danger' : 'bg-success' }}">
                                        {{ number_format($rep->balance, 2) }} ج.م
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $rep->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $rep->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $rep->created_at->format('Y-m-d') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('sales-representatives.show', $rep) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('sales-representatives.edit', $rep) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('sales-representatives.destroy', $rep) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذا المندوب؟')">
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
                    {{ $representatives->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا يوجد مندوبي مبيعات مضافين</h5>
                    <p class="text-muted">ابدأ بإضافة أول مندوب مبيعات</p>
                    <a href="{{ route('sales-representatives.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة مندوب جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
