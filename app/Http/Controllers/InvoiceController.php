<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function InvoiceCreate(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            $customer_id = $request->customer_id;
            $total = $request->total;
            $vat = $request->vat;
            $payable = $request->payable;
            $discount = $request->discount;

            $invoice = Invoice::create([
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'total' => $total,
                'vat' => $vat,
                'payable' => $payable,
                'discount' => $discount,

            ]);

           $invoiceID =  $invoice->id;
           $products = $request->products;
           foreach($products as $product){
                InvoiceProduct::create([
                    'invoice_id'=>$invoiceID,
                    'product_id'=>$product['product_id'],
                    'user_id'=>$user_id,
                    'qty'=>$product['qty'],
                    'sale_price'=>$product['sale_price'],
                ]);
           }
           DB::commit();
           return response()->json(['status' => 'success', 
           'message' => 'Invoice created successfully']);

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['status' => 'error', 
            'message' => $ex->getMessage()], 500);
        }
    }
    function InvoiceSelect(Request $request){
        $invoices = Invoice::where('user_id', $request->header('id'))->with('customer')->get();
        return response()->json($invoices);
    }
    function InvoiceDetails(Request $request){
        try{
            $user_id = $request->header('id');
            $customerDetails = Customer::where('user_id', $user_id)->where('id',$request->cus_id)->first(); 
            $invoiceTotal = Invoice::where('user_id',"=", $user_id)->where('id',"=", $request->inv_id)->first();
            $invoiceProducts = InvoiceProduct::where('user_id',"=",$user_id)
            ->where('invoice_id', $request->inv_id)
            ->get();
            return array(
                'customerDetails' => $customerDetails, 
                'invoiceTotal' => $invoiceTotal,
                'invoiceProducts' => $invoiceProducts
            );
        }catch(Exception $exception){
            
        }
        
    }
    function InvoiceDelete(Request $request){
        DB::beginTransaction();
        try {
            $user_id=$request->header('id');
            InvoiceProduct::where('invoice_id',$request->input('inv_id'))
                ->where('user_id',$user_id)
                ->delete();
            Invoice::where('id',$request->input('inv_id'))->delete();
            DB::commit();
           return response()->json(['status' => 'success', 
           'message' => 'Invoice deleted successfully']);
        }
        catch (Exception $e){
            DB::rollBack();
            return 0;
        }
    }
}
