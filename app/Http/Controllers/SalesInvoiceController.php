<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesRepresentative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesInvoiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $invoicesQuery = SalesInvoice::with(['customer', 'salesRepresentative', 'items.product']);
        
        // If user is sales representative, show only their invoices
        if ($user->role === 'sales_representative') {
            $salesRep = SalesRepresentative::where('user_id', $user->id)->first();
            if ($salesRep) {
                $invoicesQuery->where('sales_representative_id', $salesRep->id);
            }
        }
        
        $invoices = $invoicesQuery->orderBy('created_at', 'desc')->paginate(10);
        
        return view('sales-invoices.index', compact('invoices'));
    }

    public function create()
    {
        $user = Auth::user();
        $customers = Customer::all();
        $products = Product::where('is_active', true)->get();
        
        // If user is sales representative, show only their own data
        $currentSalesRep = $this->getCurrentSalesRepresentative();
        if ($currentSalesRep) {
            $salesRepresentatives = collect([$currentSalesRep]);
        } else {
            $salesRepresentatives = SalesRepresentative::all();
        }
        
        return view('sales-invoices.create', compact('customers', 'products', 'salesRepresentatives'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_representative_id' => 'required|exists:sales_representatives,id',
            'invoice_number' => 'required|string|max:255|unique:sales_invoices',
            'invoice_date' => 'required|date',
            'payment_status' => 'required|in:pending,partial,paid',
            'payment_type' => 'required|in:cash,credit',
            'total_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_cartons' => 'required|integer|min:0',
            'items.*.quantity_units' => 'required|integer|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Check if sales representative is trying to create invoice for another representative
        $currentSalesRep = $this->getCurrentSalesRepresentative();
        if ($currentSalesRep && $request->sales_representative_id != $currentSalesRep->id) {
            return back()->withErrors(['sales_representative_id' => 'لا يمكنك إنشاء فاتورة باسم مندوب آخر'])->withInput();
        }

        // Additional validation: ensure at least one item has quantity > 0
        $hasValidQuantity = false;
        foreach ($request->items as $item) {
            if (($item['quantity_cartons'] ?? 0) > 0 || ($item['quantity_units'] ?? 0) > 0) {
                $hasValidQuantity = true;
                break;
            }
        }
        
        if (!$hasValidQuantity) {
            return back()->withErrors(['items' => 'يجب إدخال كمية صحيحة لصنف واحد على الأقل'])->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $invoice = SalesInvoice::create([
                    'customer_id' => $request['customer_id'],
                    'sales_representative_id' => $request['sales_representative_id'],
                    'user_id' => Auth::id(),
                    'invoice_number' => $request['invoice_number'],
                    'invoice_date' => $request['invoice_date'],
                    'total_amount' => $request['total_amount'],
                    'paid_amount' => 0,
                    'remaining_amount' => $request['total_amount'],
                    'payment_status' => $request['payment_status'],
                    'payment_type' => $request['payment_type'],
                    'notes' => $request['notes'],
                ]);

                foreach ($request['items'] as $item) {
                    if ($item['product_id'] && (($item['quantity_cartons'] ?? 0) > 0 || ($item['quantity_units'] ?? 0) > 0)) {
                        $product = Product::find($item['product_id']);
                        $totalUnits = ($item['quantity_cartons'] * $product->units_per_carton) + $item['quantity_units'];
                        // Check stock availability
                        $availableUnits = ($product->stock_cartons * $product->units_per_carton) + $product->stock_units;
                        if ($availableUnits < $totalUnits) {
                            throw new \Exception("الكمية المطلوبة من {$product->name} غير متوفرة في المخزن");
                        }
                        $subtotal = $totalUnits * $item['unit_price'];

                        SalesInvoiceItem::create([
                            'sales_invoice_id' => $invoice->id,
                            'product_id' => $item['product_id'],
                            'quantity_cartons' => $item['quantity_cartons'],
                            'quantity_units' => $item['quantity_units'],
                            'unit_price' => $item['unit_price'],
                            'total_price' => $subtotal,
                        ]);

                        // Update product stock
                        $product->removeStock($item['quantity_cartons'], $item['quantity_units']);
                    }
                }
            });
            return redirect()->route('sales-invoices.index')
                ->with('success', 'تم إنشاء فاتورة البيع بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['items' => $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $invoice = SalesInvoice::with(['customer', 'salesRepresentative', 'items.product', 'payments'])->findOrFail($id);
        
        // Check permissions for sales representative
        $user = Auth::user();
        if ($user->role === 'sales_representative') {
            $salesRep = SalesRepresentative::where('user_id', $user->id)->first();
            if (!$salesRep || $invoice->sales_representative_id !== $salesRep->id) {
                abort(403, 'غير مسموح لك بعرض هذه الفاتورة');
            }
        }
        
        return view('sales-invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = SalesInvoice::with(['items'])->findOrFail($id);
        
        // Check permissions for sales representative
        $user = Auth::user();
        $currentSalesRep = $this->getCurrentSalesRepresentative();
        if ($currentSalesRep && $invoice->sales_representative_id !== $currentSalesRep->id) {
            abort(403, 'غير مسموح لك بتعديل هذه الفاتورة');
        }
        
        $customers = Customer::all();
        $products = Product::all();
        
        // If user is sales representative, show only their own data
        if ($currentSalesRep) {
            $salesRepresentatives = collect([$currentSalesRep]);
        } else {
            $salesRepresentatives = SalesRepresentative::all();
        }
        
        return view('sales-invoices.edit', compact('invoice', 'customers', 'products', 'salesRepresentatives'));
    }

    public function update(Request $request, $id)
    {
        $invoice = SalesInvoice::findOrFail($id);
        
        // Check permissions for sales representative
        $currentSalesRep = $this->getCurrentSalesRepresentative();
        if ($currentSalesRep && $invoice->sales_representative_id !== $currentSalesRep->id) {
            abort(403, 'غير مسموح لك بتعديل هذه الفاتورة');
        }
        
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_representative_id' => 'required|exists:sales_representatives,id',
            'invoice_number' => 'required|string|max:255|unique:sales_invoices,invoice_number,' . $id,
            'invoice_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_cartons' => 'required|integer|min:0',
            'items.*.quantity_units' => 'required|integer|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Check if sales representative is trying to assign invoice to another representative
        if ($currentSalesRep && isset($currentSalesRep->id) && $request['sales_representative_id'] != $currentSalesRep->id) {
            return back()->withErrors(['sales_representative_id' => 'لا يمكنك تعديل مندوب المبيعات'])->withInput();
        }

        try {
            DB::transaction(function () use ($request, $invoice) {
                // Restore old stock quantities
                foreach ($invoice->items as $item) {
                    $product = Product::find($item->product_id);
                    // استرجاع الكمية القديمة (كراتين وقطع)
                    if ($product) {
                        $product->addStock($item->quantity_cartons ?? 0, $item->quantity_units ?? 0);
                    }
                }

                // Delete old items
                $invoice->items()->delete();

                // Update invoice
                $invoice->update([
                    'customer_id' => $request['customer_id'],
                    'sales_representative_id' => $request['sales_representative_id'],
                    'invoice_number' => $request['invoice_number'],
                    'invoice_date' => $request['invoice_date'],
                    'notes' => $request['notes'],
                ]);

                $totalAmount = 0;
                foreach ($request['items'] as $item) {
                    if ($item['product_id'] && (($item['quantity_cartons'] ?? 0) > 0 || ($item['quantity_units'] ?? 0) > 0)) {
                        $product = Product::find($item['product_id']);
                        $totalUnits = ($item['quantity_cartons'] * $product->units_per_carton) + $item['quantity_units'];

                        // Check stock availability
                        $availableUnits = ($product->stock_cartons * $product->units_per_carton) + $product->stock_units;
                        if ($availableUnits < $totalUnits) {
                            throw new \Exception("الكمية المطلوبة من {$product->name} غير متوفرة في المخزن");
                        }

                        $subtotal = $totalUnits * $item['unit_price'];
                        $totalAmount += $subtotal;

                        SalesInvoiceItem::create([
                            'sales_invoice_id' => $invoice->id,
                            'product_id' => $item['product_id'],
                            'quantity_cartons' => $item['quantity_cartons'],
                            'quantity_units' => $item['quantity_units'],
                            'unit_price' => $item['unit_price'],
                            'total_price' => $subtotal,
                        ]);

                        // Update product stock
                        $product->removeStock($item['quantity_cartons'], $item['quantity_units']);
                    }
                }

                $paidAmount = $invoice->paid_amount;
                $invoice->update([
                    'total_amount' => $totalAmount,
                    'remaining_amount' => $totalAmount - $paidAmount
                ]);
            });
            return redirect()->route('sales-invoices.index')
                ->with('success', 'تم تحديث فاتورة البيع بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['items' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $invoice = SalesInvoice::with(['items', 'payments'])->findOrFail($id);
        
        // Check if invoice has payments
        if ($invoice->payments->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الفاتورة لوجود مدفوعات مرتبطة بها');
        }
        
        DB::transaction(function () use ($invoice) {
            // Restore stock quantities
            foreach ($invoice->items as $item) {
                $product = Product::find($item->product_id);
                $product->addStock(0, $item->quantity); // Add units back
            }
            
            $invoice->delete();
        });

        return redirect()->route('sales-invoices.index')
            ->with('success', 'تم حذف فاتورة البيع بنجاح');
    }

    /**
     * Get the current sales representative for the authenticated user
     */
    private function getCurrentSalesRepresentative()
    {
        $user = Auth::user();
        if ($user->role === 'sales_representative') {
            return SalesRepresentative::where('user_id', $user->id)->first();
        }
        return null;
    }

    public function print($id)
    {
        $invoice = SalesInvoice::with(['customer', 'salesRepresentative', 'items.product', 'payments', 'user'])->findOrFail($id);
        
        // Check permissions for sales representative
        $user = Auth::user();
        if ($user->role === 'sales_representative') {
            $salesRep = SalesRepresentative::where('user_id', $user->id)->first();
            if (!$salesRep || $invoice->sales_representative_id !== $salesRep->id) {
                abort(403, 'غير مسموح لك بطباعة هذه الفاتورة');
            }
        }
        
        return view('sales-invoices.print', compact('invoice'));
    }
}
