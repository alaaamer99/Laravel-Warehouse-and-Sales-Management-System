<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SalesRepresentativeController;
use App\Http\Controllers\PurchaseInvoiceController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Direct settings route (redirect to profile with settings tab)
    Route::get('/settings', function() {
        return redirect()->route('profile.edit', ['tab' => 'settings']);
    })->middleware('role:super_admin,admin')->name('settings.index');
    
    // Settings routes (Admin and Super Admin only)
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
    
    // Super Admin and Admin routes
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::resource('suppliers', SupplierController::class);
        Route::resource('products', ProductController::class);
        Route::resource('customers', CustomerController::class);
        Route::resource('sales-representatives', SalesRepresentativeController::class);
        Route::resource('purchase-invoices', PurchaseInvoiceController::class);
        Route::get('purchase-invoices/{id}/print', [PurchaseInvoiceController::class, 'print'])->name('purchase-invoices.print');
    });
    
    // Super Admin, Admin and Sales Representative routes
    Route::middleware('role:super_admin,admin,sales_representative')->group(function () {
        Route::resource('sales-invoices', SalesInvoiceController::class);
        Route::get('sales-invoices/{id}/print', [SalesInvoiceController::class, 'print'])->name('sales-invoices.print');
        Route::resource('payments', PaymentController::class);
        Route::get('payments/{id}/print', [PaymentController::class, 'print'])->name('payments.print');
        Route::get('payments/invoice-details', [PaymentController::class, 'getInvoiceDetails'])->name('payments.invoice-details');
        
        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('reports/sales/print', [ReportController::class, 'printSalesReport'])->name('reports.sales.print');
        Route::get('reports/purchases', [ReportController::class, 'purchaseReport'])->name('reports.purchases');
        Route::get('reports/purchases/print', [ReportController::class, 'printPurchasesReport'])->name('reports.purchases.print');
        Route::get('reports/customers', [ReportController::class, 'customerReport'])->name('reports.customers');
        Route::get('reports/customers/print', [ReportController::class, 'printCustomersReport'])->name('reports.customers.print');
        Route::get('reports/payments', [ReportController::class, 'paymentReport'])->name('reports.payments');
        Route::get('reports/payments/print', [ReportController::class, 'printPaymentsReport'])->name('reports.payments.print');
        Route::get('reports/representatives', [ReportController::class, 'representativeReport'])->name('reports.representatives');
        Route::get('reports/representatives/print', [ReportController::class, 'printRepresentativesReport'])->name('reports.representatives.print');
        Route::get('reports/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
        Route::get('reports/inventory/print', [ReportController::class, 'printInventoryReport'])->name('reports.inventory.print');
        Route::get('reports/profit', [ReportController::class, 'profitReport'])->name('reports.profit');
        Route::get('reports/profit/print', [ReportController::class, 'printProfitReport'])->name('reports.profit.print');
    });
});

require __DIR__.'/auth.php';
