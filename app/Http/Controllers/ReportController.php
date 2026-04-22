<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\SalesRepresentative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function salesReport(Request $request)
    {


    }

    public function purchaseReport(Request $request)
    {

    }

    public function paymentReport(Request $request)
    {



    }

    public function inventoryReport()
    {

    }

    public function customerReport(Request $request)
    {




    }

    public function representativeReport(Request $request)
    {


    }

    public function profitReport(Request $request)
    {

    }

    public function printSalesReport(Request $request)
    {

    }

    public function printPurchasesReport(Request $request)
    {

    }

    public function printCustomersReport(Request $request)
    {

    }

    public function printPaymentsReport(Request $request)
    {

    }

    public function printRepresentativesReport(Request $request)
    {


    }

    public function printInventoryReport()
    {

    }

    public function printProfitReport(Request $request)
    {



    }
}
