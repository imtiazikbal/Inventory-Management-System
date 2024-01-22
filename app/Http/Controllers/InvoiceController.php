<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Category;
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
            $invoiceProduct=InvoiceProduct::where('invoice_id',$request->input('inv_id'))
            ->where('user_id',$user_id)->with('product')
            ->get();
            return array(
                'customer' => $customerDetails, 
                'invoice' => $invoiceTotal,
                'product' => $invoiceProduct
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

    function Summery(Request $request){
        try{
            $user_id = $request->header('id');
            $productCount = Product::where('user_id', $user_id)->count();
            $categoryCount = Category::where('user_id', $user_id)->count();
            $CustomerCount = Customer::where('user_id', $user_id)->count();
            $InvoiceCount = Invoice::where('user_id', $user_id)->count();
            $Total = Invoice::where('user_id', $user_id)->sum('total');
            $TotalVat = Invoice::where('user_id', $user_id)->sum('vat');
            $payable = Invoice::where('user_id', $user_id)->sum('payable');
            return array(
                'product' => $productCount, 
                'category' => $categoryCount,
                'customer' => $CustomerCount,
                'invoice' => $InvoiceCount,
                'total' => $Total,
                'vat' => $TotalVat,
                'payable' => $payable
                
            );
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
