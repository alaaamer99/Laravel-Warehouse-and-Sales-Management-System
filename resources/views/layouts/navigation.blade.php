<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
            @if($globalSettings && $globalSettings->logo_url)
                <img src="{{ $globalSettings->logo_url }}" alt="{{ $globalSettings->company_name }}" class="me-2" style="max-height: 40px;">
            @else
                <i class="fas fa-store me-2"></i>
            @endif
            {{ $globalSettings->company_name ?? 'بهجة للمنظفات' }}
        </a>

        <!-- Toggle button for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>
                        لوحة التحكم
                    </a>
                </li>

                @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                    <!-- Suppliers -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                            <i class="fas fa-truck me-1"></i>
                            الموردين
                        </a>
                    </li>

                    <!-- Products -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="fas fa-boxes me-1"></i>
                            الأصناف
                        </a>
                    </li>

                    <!-- Customers -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                            <i class="fas fa-users me-1"></i>
                            العملاء
                        </a>
                    </li>

                    <!-- Sales Representatives -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('sales-representatives.*') ? 'active' : '' }}" href="{{ route('sales-representatives.index') }}">
                            <i class="fas fa-user-tie me-1"></i>
                            مندوبي المبيعات
                        </a>
                    </li>

                    <!-- Purchase Invoices -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('purchase-invoices.*') ? 'active' : '' }}" href="{{ route('purchase-invoices.index') }}">
                            <i class="fas fa-file-invoice me-1"></i>
                            فواتير الشراء
                        </a>
                    </li>
                @endif

                <!-- Sales Invoices -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sales-invoices.*') ? 'active' : '' }}" href="{{ route('sales-invoices.index') }}">
                        <i class="fas fa-receipt me-1"></i>
                        فواتير البيع
                    </a>
                </li>

                <!-- Payments -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                        <i class="fas fa-money-bill-wave me-1"></i>
                        المدفوعات
                    </a>
                </li>

                <!-- Reports Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-chart-bar me-1"></i>
                        التقارير
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                        <li><a class="dropdown-item" href="{{ route('reports.sales') }}">
                            <i class="fas fa-chart-line me-2"></i>تقرير المبيعات
                        </a></li>
                        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                            <li><a class="dropdown-item" href="{{ route('reports.purchases') }}">
                                <i class="fas fa-shopping-cart me-2"></i>تقرير المشتريات
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('reports.inventory') }}">
                                <i class="fas fa-warehouse me-2"></i>تقرير المخزون
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('reports.customers') }}">
                                <i class="fas fa-user-friends me-2"></i>تقرير العملاء
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('reports.profit') }}">
                                <i class="fas fa-dollar-sign me-2"></i>تقرير الأرباح
                            </a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('reports.payments') }}">
                            <i class="fas fa-coins me-2"></i>تقرير المدفوعات
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.representatives') }}">
                            <i class="fas fa-user-chart me-2"></i>تقرير المندوبين
                        </a></li>
                    </ul>
                </li>
            </ul>

            <!-- User dropdown -->
            <ul class="navbar-nav">
                @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                    <!-- Settings -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile.edit') && request()->has('tab') && request()->get('tab') === 'settings' ? 'active' : '' }}" 
                           href="{{ route('settings.index') }}">
                            <i class="fas fa-cog me-1"></i>
                            الإعدادات العامة
                        </a>
                    </li>
                @endif
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        {{ Auth::user()->name }}
                        <span class="badge bg-secondary ms-1">
                            @if(auth()->user()->role === 'super_admin')
                                سوبر أدمن
                            @elseif(auth()->user()->role === 'admin')
                                أدمن
                            @else
                                مندوب مبيعات
                            @endif
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit me-2"></i>الملف الشخصي
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
