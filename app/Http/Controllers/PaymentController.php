<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SalesInvoice;
use App\Models\SalesRepresentative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $paymentsQuery = Payment::with(['salesInvoice.customer', 'salesInvoice.salesRepresentative']);
        
        // If user is sales representative, show only their payments
        if ($user->role === 'sales_representative') {
            $salesRep = SalesRepresentative::where('user_id', $user->id)->first();
            if ($salesRep) {
                $paymentsQuery->whereHas('salesInvoice', function ($query) use ($salesRep) {
                    $query->where('sales_representative_id', $salesRep->id);
                });
            }
        }
        
        $payments = $paymentsQuery->orderBy('created_at', 'desc')->paginate(10);
        
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $user = Auth::user();
        $invoicesQuery = SalesInvoice::with(['customer', 'salesRepresentative'])
            ->where('remaining_amount', '>', 0);
        
        // If user is sales representative, show only their invoices
        if ($user->role === 'sales_representative') {
            $salesRep = SalesRepresentative::where('user_id', $user->id)->first();
            if ($salesRep) {
                $invoicesQuery->where('sales_representative_id', $salesRep->id);
            }
        }
        
        $invoices = $invoicesQuery->get();
        
        return view('payments.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sales_invoice_id' => 'required|exists:sales_invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,check,bank_transfer,credit_card',
            'reference_number' => 'nullable|string|max:255',
        ]);

        $invoice = SalesInvoice::findOrFail($request->sales_invoice_id);
        
        // Check if payment amount doesn't exceed remaining amount
        if ($request->amount > $invoice->remaining_amount) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'مبلغ الدفع لا يمكن أن يكون أكبر من المبلغ المتبقي');
        }
        
        // Check permissions for sales representative
        $user = Auth::user();
        if ($user->role === 'sales_representative') {
            $salesRep = SalesRepresentative::where('user_id', $user->id)->first();
            if (!$salesRep || $invoice->sales_representative_id !== $salesRep->id) {
                abort(403, 'غير مسموح لك بإضافة دفعة لهذه الفاتورة');
            }
        }

        DB::transaction(function () use ($request, $invoice) {
            // Generate payment number
            $paymentNumber = 'PAY-' . date('YmdHis') . '-' . rand(100, 999);
            
            $payment = Payment::create([
                'payment_number' => $paymentNumber,
                'payment_type' => 'customer_payment',
                'customer_id' => $invoice->customer_id,
                'sales_representative_id' => $invoice->sales_representative_id,
                'sales_invoice_id' => $request->sales_invoice_id,
                'user_id' => Auth::id(),
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
            ]);

            // Update invoice amounts
            $invoice->increment('paid_amount', $request->amount);
            $invoice->decrement('remaining_amount', $request->amount);
            
            // Update invoice status
            if ($invoice->remaining_amount <= 0) {
                $invoice->update(['payment_status' => 'paid']);
            } elseif ($invoice->paid_amount > 0) {
                $invoice->update(['payment_status' => 'partial']);
            }
        });

        return redirect()->route('payments.index')
            ->with('success', 'تم تسجيل الدفعة بنجاح');
    }

    public function show($id)
    {
        $payment = Payment::with(['salesInvoice.customer', 'salesInvoice.salesRepresentative'])->findOrFail($id);
        
        // Check permissions for sales representative
        $user = Auth::user();
        if ($user->role === 'sales_representative') {
            $salesRep = SalesRepresentative::where('user_id', $user->id)->first();
            if (!$salesRep || $payment->salesInvoice->sales_representative_id !== $salesRep->id) {
                abort(403, 'غير مسموح لك بعرض هذه الدفعة');
            }
        }
        
        return view('payments.show', compact('payment'));
    }

    public function edit($id)
    {
        $payment = Payment::with('salesInvoice')->findOrFail($id);
        
        // Check permissions for sales representative
        $user = Auth::user();
        if ($user->role === 'sales_representative') {
            $salesRep = SalesRepresentative::where('user_id', $user->id)->first();
            if (!$salesRep || $payment->salesInvoice->sales_representative_id !== $salesRep->id) {
                abort(403, 'غير مسموح لك بتعديل هذه الدفعة');
            }
        }
        
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::with('salesInvoice')->findOrFail($id);
        
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,check,bank_transfer',
            'reference_number' => 'nullable|string|max:255',
        ]);

        $invoice = $payment->salesInvoice;
        $oldAmount = $payment->amount;
        $newAmount = $request->amount;
        
        // Calculate available amount for this payment
        $availableAmount = $invoice->remaining_amount + $oldAmount;
        
        if ($newAmount > $availableAmount) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'مبلغ الدفع لا يمكن أن يكون أكبر من المبلغ المتاح');
        }

        DB::transaction(function () use ($request, $payment, $invoice, $oldAmount, $newAmount) {
            // Update payment
            $payment->update([
                'amount' => $newAmount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
            ]);

            // Adjust invoice amounts
            $difference = $newAmount - $oldAmount;
            $invoice->increment('paid_amount', $difference);
            $invoice->decrement('remaining_amount', $difference);
            
            // Update invoice status
            if ($invoice->remaining_amount <= 0) {
                $invoice->update(['payment_status' => 'paid']);
            } elseif ($invoice->paid_amount > 0) {
                $invoice->update(['payment_status' => 'partial']);
            } else {
                $invoice->update(['payment_status' => 'pending']);
            }
        });

        return redirect()->route('payments.index')
            ->with('success', 'تم تحديث الدفعة بنجاح');
    }

    public function destroy($id)
    {
        $payment = Payment::with('salesInvoice')->findOrFail($id);
        $invoice = $payment->salesInvoice;
        
        // Check permissions for sales representative
        $user = Auth::user();
        if ($user->role === 'sales_representative') {
            $salesRep = SalesRepresentative::where('user_id', $user->id)->first();
            if (!$salesRep || $invoice->sales_representative_id !== $salesRep->id) {
                abort(403, 'غير مسموح لك بحذف هذه الدفعة');
            }
        }

        DB::transaction(function () use ($payment, $invoice) {
            // Adjust invoice amounts
            $invoice->decrement('paid_amount', $payment->amount);
            $invoice->increment('remaining_amount', $payment->amount);
            
            // Update invoice status
            if ($invoice->remaining_amount >= $invoice->total_amount) {
                $invoice->update(['payment_status' => 'pending']);
            } elseif ($invoice->paid_amount > 0) {
                $invoice->update(['payment_status' => 'partial']);
            }
            
            $payment->delete();
        });

        return redirect()->route('payments.index')
            ->with('success', 'تم حذف الدفعة بنجاح');
    }

    public function getInvoiceDetails(Request $request)
    {
        $invoice = SalesInvoice::with(['customer', 'salesRepresentative'])
            ->findOrFail($request->invoice_id);
        
        return response()->json([
            'customer_name' => $invoice->customer->name,
            'total_amount' => $invoice->total_amount,
            'paid_amount' => $invoice->paid_amount,
            'remaining_amount' => $invoice->remaining_amount,
            'representative_name' => $invoice->salesRepresentative->name,
        ]);
    }

    public function print($id)
    {
        $payment = Payment::with(['salesInvoice.customer', 'salesInvoice.salesRepresentative', 'user'])->findOrFail($id);
        
        // Check permissions for sales representative
        $user = Auth::user();
        if ($user->role === 'sales_representative') {
            $salesRep = SalesRepresentative::where('user_id', $user->id)->first();
            if (!$salesRep || $payment->salesInvoice->sales_representative_id !== $salesRep->id) {
                abort(403, 'غير مسموح لك بطباعة هذه الدفعة');
            }
        }
        
        return view('payments.print', compact('payment'));
    }
}
