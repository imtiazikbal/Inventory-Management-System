<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

    function ProductList(Request $request){
        try{
            $user_id = $request->header('id');
            return Product::where('user_id',$user_id)->get();
        }catch(Exception $exception){
            return $exception->getMessage();
        }
    }
    function ProductCreate(Request $request){
        
        try{
            $user_id=$request->header('id');

            // Prepare File Name & Path
            $img=$request->file('img');
    
            $t=time();
            $file_name=$img->getClientOriginalName();
            $img_name="{$user_id}-{$t}-{$file_name}";
            $img_url="uploads/{$img_name}";
    
    
            // Upload File
            $img->move(public_path('uploads'),$img_name);
    
    
            // Save To Database
            Product::create([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'unit'=>$request->input('unit'),
                'img_url'=>$img_url,
                'category_id'=>$request->input('category_id'),
                'user_id'=>$user_id
            ]);
            return response()->json(['status'=>'success']);
         
        }catch(Exception $exception){
            return $exception->getMessage();
            
        }
    }
    function ProductDelete(Request $request){
        try{
            $user_id = $request->header('id');
            $product_id = $request->input('id');
            $file_path = $request->input('file_path');
            File::delete($file_path);


            Product::where('id',$product_id)->where('user_id',$user_id)->delete();
            return response()->json(['status'=>'success']);
            
        }catch(Exception $exception){
            return $exception->getMessage();
            
        }
    }
    function ProductUpdate(Request $request){
        
        try{
            $user_id = $request->header('id');
            $product_id = $request->input('id');

            if($request->hasFile('img')){
                // Upload New File
                $img = $request->file('img');
                $t = time();
                $file_name = $img->getClientOriginalName();
                $img_name = "{$user_id}-{$t}-{$file_name}";
                $img_url = "uploads/{$img_name}";
                $img->move(public_path('uploads'), $img_name);
          
            //delete old file
            $file_path = $request->input('file_path');
            File::delete($file_path);
           
           Product::where('id',$product_id)->where('user_id',$user_id)->update([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'unit'=>$request->input('unit'),
                'img_url'=>$img_url,
                'category_id'=>$request->input('category_id'),
                
            ]);
            return response()->json([
                'status'=>'success',
                'message'=>"Product Updated with image successfully"
            ]);
        }else{
            Product::where('id',$product_id)->where('user_id',$user_id)->update([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'unit'=>$request->input('unit'),
                'category_id'=>$request->input('category_id'),
                
            ]);
            return response()->json([
                'status'=>'success',
                'message'=>"Product Updated without image successfully"
            ]);
          
        }
    }catch(Exception $exception){
        return $exception->getMessage();
        
    }
}
function ProductByID(Request $request){
    try{
        $user_id = $request->header('id');
        $product_id = $request->input('id');
        $rows = Product::where('id',$product_id)->where('user_id',$user_id)->first();
        return response()->json(['status'=>'success',
        'rows'=> $rows
        
        
    ]);
    }catch(Exception $exception){
        return $exception->getMessage();
        
    }
}
}
