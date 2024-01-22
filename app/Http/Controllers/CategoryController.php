<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
   
    public function categoryList(Request $request){
        try{
            $user_id=$request->header('id');
          
            $rows =  Category::where('user_id',$user_id)->get();
            return response()->json(['status' => "success", 'rows' => $rows]);
            
        }catch(Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    function CategoryCreate(Request $request){
        try{
           $name = $request->name;
           $user_id = $request->header('id');
           Category::create([
            'name' => $name,
            'user_id' => $user_id
           ]);
           return response()->json('Category created successfully');
        }catch(Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    function CategoryUpdate(Request $request){
        try{
           $user_id = $request->header('id');
           $id =$request->input('id');
            $name = $request->name;
          Category::where('id',$id)->where('user_id',$user_id)->update([
            'name' => $name
          ]);
            return response()->json('Category updated successfully');
        }catch(Exception $exception){
            return response()->json($exception->getMessage());
        }
    }
    function CategoryDelete(Request $request){
        try{
            $user_id = $request->header('id');
            $category_id =$request->input('id');
            Category::where('id',$category_id)->where('user_id',$user_id)->delete(); 
            return response()->json('Category deleted successfully');
        }catch(Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    function CategoryByID(Request $request){
        try{
            
            $category_id =$request->input('id');
            $rows =  Category::where('id',$category_id)->first();
            return response()->json(['status' => "success", 'rows' => $rows]);
            
        }catch(Exception $exception){
            return response()->json($exception->getMessage());
        }
    }
}
