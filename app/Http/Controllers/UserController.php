<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\MailOTP;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function userRegistration(Request $request){
       try{
        User::create([
            'firstName'=>$request->input('firstName'),
            'lastName'=>$request->input('lastName'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
            'password'=>$request->input('password'),
        ]);
        return response()->json(['status'=>'success','message'=>'User Registration Successfully']);
       }catch(Exception $exception){
        return response()->json(['status'=>'error','message'=>$exception->getMessage()]);
       }
       
    }
    public function userLogin(Request $request){
        $count = User::where('email','=',$request->input('email'))
        ->where('password','=',$request->input('password'))
        ->select('id')->first();
        $user = User::where('email',$request->input('email'))->first();
        if($count!==null){
            $token = JWTToken::CreateToken($request->input('email'),$count->id);
            return response()->json([
                'status'=>'success',
                'message'=>'User Login Successfully',
            
            ])->cookie('token', $token,60*60*24*30,'/');
            
        }else     
        {
            return response()->json([
                'status'=>'error',
                'message'=>'Unauthorized'
            ]);
        }
     
    }
    function OtpCodeSend(Request $request){
        $email = $request->input('email');
        $count = User::where('email','=',$email)->count();
        $otp = rand(1111,9999);
        if($count==1){
            //MAIL send
            Mail::to($email)->send(new MailOTP($otp));
            //and otp filed insert database;
            User::where('email',$email)->update([
                'otp'=>$otp
            ]);
            return response()->json([
                'status'=>'success',
                'message'=>'4 Digit Code Send Successfully']);
        }
        else{
            return response()->json([
                'status'=>'error',
                'message'=>'Unauthorized']);
        }
    }
    function varifyOtp(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email',$email)->where('otp',$otp)->count();
      
        // add 5 minutes 
       
        if($count==1)
      {
            $token = JWTToken::CreateTokenForSetPassword($email);
            return response()->json([
                'status'=>'success',
                'message'=>'OTP Varified Successfully',
               
            ])->cookie('token',$token,60*60*24*30,'/');
          
        } else{
            return response()->json([
                'status'=>'error',
                'message'=>'OTP Expired']);
           }
       
    }
    function passWordReset(Request $request){
        
       try{
        $email = $request->header('email');
        $password = $request->input('password');
        User::where('email','=',$email)->update([
            'password'=>$password,
            'otp'=>"0"
        ]);
        return response()->json([
            'status'=>'success',
            'message'=>'Password Reset Successfully'
        ]);
       }catch(Exception $exception){
        return response()->json([
            'status'=>'error',
            'message'=>'Unauthorized'
        ]);
        
       }
        
    }
    function userLogout(){
        return redirect('/userLogin-page')->cookie('token', '', -1,);
    }
    function userProfile(Request $request){
        $email = $request->header('email');
        $user = User::where('email',$email)->first();
        return response()->json([
            'status'=>'success',
            'message'=>'User Profile Details',
            'data'=>$user
        ]);
    }
    function userProfileUpdate(Request $request){
        
        $email = $request->header('email');
         User::where('email','=',$email)->update([
            'firstName'=>$request->input('firstName'),
            'lastName'=>$request->input('lastName'),
            'password'=>$request->input('password'),
            'mobile'=>$request->input('mobile')
        ]);
        return response()->json([
            'status'=>'success',
            'message'=>'User Profile Update Successfully'
        ]);
    }

    ///View Page here
    function userRegistrationPage(){
        return view('pages.auth.registration-page');
    }
    function userLoginPage(){
        return view('pages.auth.login-page');
    }
    function sentOTPPage(){
        return view('pages.auth.send-otp-page');
    } 
    function varifyOtpPage(){
        return view('pages.auth.verify-otp-page');
    }
    function reserPasswordPage(){
        return view('pages.auth.reset-pass-page');
    }
    function Dashboard(){
        return view('pages.dashboard.dashboard-page');
    }
    function profilePage(){
        return view('pages.dashboard.profile-page');
    }
    function userListApi(Request $request){
      try{
        $users = User::all();
        return response()->json([
            'status'=>'success',
            'data'=>$users
        ]);
      }catch(Exception $exception){
        return response()->json([
            'status'=>'error',
            'message'=>$exception->getMessage()
        ]);
        
      }
   
}
}
