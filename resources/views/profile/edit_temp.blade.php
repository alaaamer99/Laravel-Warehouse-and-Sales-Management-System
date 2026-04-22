@extends('layouts.app')

@section('title', 'الملف الشخصي - شركة بهجة')
@section('page-title', 'الملف الشخصي')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-user me-2"></i>
            الملف الشخصي
        </h1>
    </div>

    <div class="card">
        <div class="card-body">
            <p>صفحة البروفايل تعمل بشكل صحيح!</p>
            <p>الاسم: {{ Auth::user()->name }}</p>
            <p>البريد: {{ Auth::user()->email }}</p>
        </div>
    </div>
</div>
@endsection
