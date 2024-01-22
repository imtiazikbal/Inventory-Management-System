<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{

    public static function CreateToken($userEmail,$userID){
        $key =env('JTW_TOKEN');
        $payload=[
            'iss'=>'laravel-token',
            'iat'=>time(),
            'exp'=>time()+60*60,
            'userEmail'=>$userEmail,
            'userID'=>$userID
            
        ];
       return JWT::encode($payload,$key,'HS256');
    }

    public static function CreateTokenForSetPassword($userEmail){
        $key =env('JTW_TOKEN');
        $payload=[
            'iss'=>'laravel-token',
            'iat'=>time(),
            'exp'=>time()+60*20,
            'userEmail'=>$userEmail,
            'userID'=>''
            
        ];
       return JWT::encode($payload,$key,'HS256');
    }



    public static function VerifyToken($token)
    {
        try {
          if($token==null){
            return 'unauthorized';
          }
            $key = env('JTW_TOKEN');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return $decoded;
            
        }
        catch (Exception $exeption){
            return 'unauthorized';
        }
    }

}
