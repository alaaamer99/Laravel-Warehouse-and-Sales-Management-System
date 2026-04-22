<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseInvoiceController extends Controller
{
    public function index()
    {
        $invoices = PurchaseInvoice::with(['supplier', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('purchase-invoices.index', compact('invoices'));
    }

    public function create()
    {

    }

    public function store(Request $request)
    {




    }

    public function show($id)
    {

    }

    public function edit($id)
    {


    }

    public function update(Request $request, $id)
    {




    }

    public function destroy($id)
    {

    }

    public function print($id)
    {

    }
}
