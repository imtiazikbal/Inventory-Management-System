<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function CustomerList(Request $request){
        try{
            $user_id=$request->header('id');
          
            $rows =  Customer::where('user_id',$user_id)->get();
            return response()->json(['status' => "success", 'rows' => $rows]);
            
        }catch(Exception $exception){
            return response()->json($exception->getMessage());
        }
    }
    public function CustomerCreate(Request $request)
    {
        try {
            $name = $request->name;
            $user_id = $request->header('id');
            Customer::create([
                'name' => $name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_id' => $user_id,
            ]);
            return response()->json('Customer created successfully');
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }
    public function CustomerUpdate(Request $request)
    {
        try {
            $user_id = $request->header('id');
            $id = $request->input('id');
            
            $name = $request->name;
            Customer::where('id', $id)->where('user_id', $user_id)->update([
                'name' => $name,
                'email' => $request->email,
                'mobile' => $request->mobile,
            ]);
            return response()->json('Customer updated successfully');
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }
    function CustomerDelete(Request $request){
        try{
            $user_id = $request->header('id');
            $id =$request->input('id');
            Customer::where('id',$id)->where('user_id',$user_id)->delete(); 
            return response()->json('Category deleted successfully');
        }catch(Exception $exception){
            return response()->json($exception->getMessage());
        }
    }
    function CustomerByID(Request $request){
        try{
            
            $id =$request->input('id');
            $rows =  Customer::where('id',$id)->first();
            return response()->json(['status' => "success", 'rows' => $rows]);
            
        }catch(Exception $exception){
            return response()->json($exception->getMessage());
        }
    }
}
