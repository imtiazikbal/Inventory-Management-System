<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function report(Request $request)
    {
        try {
            $user_id = $request->header('id');
            $FormDate = date('Y-m-d', strtotime($request->FormDate));
            $ToDate = date('Y-m-d', strtotime($request->ToDate));
            $total = Invoice::where('user_id', $user_id)->whereDate('created_at', '>=', $FormDate)->whereDate('created_at', '<=', $ToDate)->sum('total');
            $vat = Invoice::where('user_id', $user_id)->whereDate('created_at', '>=', $FormDate)->whereDate('created_at', '<=', $ToDate)->sum('vat');
            $payable = Invoice::where('user_id', $user_id)->whereDate('created_at', '>=', $FormDate)->whereDate('created_at', '<=', $ToDate)->sum('payable');
            $discount = Invoice::where('user_id', $user_id)->whereDate('created_at', '>=', $FormDate)->whereDate('created_at', '<=', $ToDate)->sum('discount');
            $list = Invoice::where('user_id', $user_id)->whereDate('created_at', '>=', $FormDate)->whereDate('created_at', '<=', $ToDate)->with('customer')->get();
            $data = [
                'total' => $total,
                'vat' => $vat,
                'payable' => $payable,
                'discount' => $discount,
                'list' => $list,
                'FormDate' => $FormDate,
                'ToDate' => $ToDate,
            ];
            $pdf = Pdf::loadView('report.SalesReport', $data);
            return $pdf->download('SalesReport.pdf');
        } catch (Exception $exception) {

        }
    }
}
