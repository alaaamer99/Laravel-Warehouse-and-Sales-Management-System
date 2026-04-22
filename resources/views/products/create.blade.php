@extends('layouts.app')

@section('title', 'إضافة صنف جديد - شركة بهجة')
@section('page-title', 'إضافة صنف جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        إضافة صنف جديد
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-box me-1"></i>
                                    اسم الصنف *
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="product_number" class="form-label">
                                    <i class="fas fa-barcode me-1"></i>
                                    رقم المنتج
                                </label>
                                <input type="text" class="form-control @error('product_number') is-invalid @enderror" 
                                       id="product_number" name="product_number" value="{{ old('product_number') }}" 
                                       placeholder="سيتم توليد رقم تلقائياً إذا ترك فارغاً">
                                @error('product_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">إذا تُرك فارغاً، سيتم توليد رقم المنتج تلقائياً</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="supplier_id" class="form-label">
                                    <i class="fas fa-truck me-1"></i>
                                    المورد *
                                </label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                        id="supplier_id" name="supplier_id" required>
                                    <option value="">اختر المورد</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="barcode" class="form-label">
                                    <i class="fas fa-qrcode me-1"></i>
                                    الباركود
                                </label>
                                <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                       id="barcode" name="barcode" value="{{ old('barcode') }}">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="units_per_carton" class="form-label">
                                    <i class="fas fa-cubes me-1"></i>
                                    عدد القطع في الكرتونة *
                                </label>
                                <input type="number" class="form-control @error('units_per_carton') is-invalid @enderror" 
                                       id="units_per_carton" name="units_per_carton" value="{{ old('units_per_carton') }}" min="1" required>
                                @error('units_per_carton')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="stock_cartons" class="form-label">
                                    <i class="fas fa-archive me-1"></i>
                                    عدد الكراتين
                                </label>
                                <input type="number" class="form-control @error('stock_cartons') is-invalid @enderror" 
                                       id="stock_cartons" name="stock_cartons" value="{{ old('stock_cartons', 0) }}" min="0">
                                @error('stock_cartons')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="stock_units" class="form-label">
                                    <i class="fas fa-cube me-1"></i>
                                    عدد القطع المفردة
                                </label>
                                <input type="number" class="form-control @error('stock_units') is-invalid @enderror" 
                                       id="stock_units" name="stock_units" value="{{ old('stock_units', 0) }}" min="0">
                                @error('stock_units')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="purchase_price" class="form-label">
                                    <i class="fas fa-dollar-sign me-1"></i>
                                    سعر الشراء (ج.م) *
                                </label>
                                <input type="number" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror" 
                                       id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" min="0" required>
                                @error('purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="wholesale_price" class="form-label">
                                    <i class="fas fa-tags me-1"></i>
                                    سعر البيع بالجملة (ج.م) *
                                </label>
                                <input type="number" step="0.01" class="form-control @error('wholesale_price') is-invalid @enderror" 
                                       id="wholesale_price" name="wholesale_price" value="{{ old('wholesale_price') }}" min="0" required>
                                @error('wholesale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="retail_price" class="form-label">
                                    <i class="fas fa-tag me-1"></i>
                                    سعر البيع بالتجزئة (ج.م) *
                                </label>
                                <input type="number" step="0.01" class="form-control @error('retail_price') is-invalid @enderror" 
                                       id="retail_price" name="retail_price" value="{{ old('retail_price') }}" min="0" required>
                                @error('retail_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="is_active" class="form-label">
                                    <i class="fas fa-toggle-on me-1"></i>
                                    حالة المنتج
                                </label>
                                <select class="form-select @error('is_active') is-invalid @enderror" 
                                        id="is_active" name="is_active">
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>نشط</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                وصف المنتج
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة للقائمة
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                حفظ المنتج
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
