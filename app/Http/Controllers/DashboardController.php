<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\SalesRepresentative;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Initialize variables
        $stats = [];
        $recentInvoices = collect();
        $recentPayments = collect();
        $lowStockProducts = collect();
        
        if ($user->role === 'sales_representative') {
            // Sales representative specific stats
            $salesRep = SalesRepresentative::where('user_id', $user->id)->first();
            
            if ($salesRep) {
                $stats['monthly_sales'] = SalesInvoice::where('sales_representative_id', $salesRep->id)
                    ->whereMonth('invoice_date', Carbon::now()->month)
                    ->sum('total_amount');
                
                $stats['monthly_collections'] = Payment::whereHas('salesInvoice', function ($query) use ($salesRep) {
                    $query->where('sales_representative_id', $salesRep->id);
                })->whereMonth('payment_date', Carbon::now()->month)->sum('amount');
                
                $stats['pending_invoices'] = SalesInvoice::where('sales_representative_id', $salesRep->id)
                    ->where('payment_status', 'pending')
                    ->count();
                
                $stats['total_customers'] = Customer::count();
                
                $recentInvoices = SalesInvoice::where('sales_representative_id', $salesRep->id)
                    ->with(['customer'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                
                $recentPayments = Payment::whereHas('salesInvoice', function ($query) use ($salesRep) {
                    $query->where('sales_representative_id', $salesRep->id);
                })->with(['salesInvoice.customer'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            } else {
                // If no sales rep found, set default values
                $stats['monthly_sales'] = 0;
                $stats['monthly_collections'] = 0;
                $stats['pending_invoices'] = 0;
                $stats['total_customers'] = Customer::count();
            }
            
            // Sales representative - set empty collection for lowStockProducts
            $lowStockProducts = collect();
        } else {
            // Admin and Super Admin stats
            $stats['monthly_sales'] = SalesInvoice::whereMonth('invoice_date', Carbon::now()->month)
                ->sum('total_amount');
            
            $stats['monthly_purchases'] = PurchaseInvoice::whereMonth('invoice_date', Carbon::now()->month)
                ->sum('total_amount');
            
            $stats['monthly_collections'] = Payment::whereMonth('payment_date', Carbon::now()->month)
                ->sum('amount');
            
            $stats['low_stock_products'] = Product::get()->filter(function ($product) {
                return $product->total_stock_units <= 10;
            })->count();
            
            $stats['total_customers'] = Customer::count();
            $stats['total_suppliers'] = Supplier::count();
            $stats['total_products'] = Product::count();
            $stats['total_representatives'] = SalesRepresentative::count();
            
            $stats['pending_invoices'] = SalesInvoice::where('payment_status', 'pending')->count();
            $stats['partially_paid_invoices'] = SalesInvoice::where('payment_status', 'partial')->count();
            
            $recentInvoices = SalesInvoice::with(['customer', 'salesRepresentative'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            $recentPayments = Payment::with(['salesInvoice.customer', 'salesInvoice.salesRepresentative'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            $lowStockProducts = Product::with('supplier')
                ->get()
                ->filter(function ($product) {
                    return $product->total_stock_units <= 10;
                })
                ->sortBy('total_stock_units')
                ->take(5);
        }
        
        return view('dashboard', compact('stats', 'recentInvoices', 'recentPayments', 'lowStockProducts'));
    }
}
