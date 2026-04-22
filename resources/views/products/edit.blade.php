@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">تعديل بيانات المنتج</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product_number" class="form-label">رقم المنتج <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('product_number') is-invalid @enderror" 
                                           id="product_number" name="product_number" value="{{ old('product_number', $product->product_number) }}" required>
                                    @error('product_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supplier_id" class="form-label">المورد <span class="text-danger">*</span></label>
                                    <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                        <option value="">اختر المورد</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="units_per_carton" class="form-label">عدد القطع في الكرتونة <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('units_per_carton') is-invalid @enderror" 
                                           id="units_per_carton" name="units_per_carton" value="{{ old('units_per_carton', $product->units_per_carton) }}" min="1" required>
                                    @error('units_per_carton')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="purchase_price" class="form-label">سعر الشراء <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror" 
                                           id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" min="0" required>
                                    @error('purchase_price')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="wholesale_price" class="form-label">سعر الجملة <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('wholesale_price') is-invalid @enderror" 
                                           id="wholesale_price" name="wholesale_price" value="{{ old('wholesale_price', $product->wholesale_price) }}" min="0" required>
                                    @error('wholesale_price')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="retail_price" class="form-label">سعر التجزئة <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('retail_price') is-invalid @enderror" 
                                           id="retail_price" name="retail_price" value="{{ old('retail_price', $product->retail_price) }}" min="0" required>
                                    @error('retail_price')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_cartons" class="form-label">عدد الكراتين <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock_cartons') is-invalid @enderror" 
                                           id="stock_cartons" name="stock_cartons" value="{{ old('stock_cartons', $product->stock_cartons) }}" min="0" required>
                                    @error('stock_cartons')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_units" class="form-label">عدد القطع المتبقية <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock_units') is-invalid @enderror" 
                                           id="stock_units" name="stock_units" value="{{ old('stock_units', $product->stock_units) }}" min="0" required>
                                    @error('stock_units')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="barcode" class="form-label">الباركود</label>
                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                           id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}">
                                    @error('barcode')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">الوصف</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-1"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
