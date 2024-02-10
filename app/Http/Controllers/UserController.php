<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Show the form for user for Register a new resource.
     */
    public function Register(Request $request)
    {
        // Validaion for check form
       $request->validate([
        'name'=>'required',
        'email'=>'required',
        'password'=>'required|confirmed',
        'tc'=>'required'
       ]);
       if(User::where('email',$request->email)->first()){
        return response([
            'massage'=>'Email already exists',
            'status' => 'failed'
        ],200);
    }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'tc'=>json_decode($request->tc),
        ]);
        $token = $user->createToken($request->email)->plainTextToken;
        return response([
            'message' => "Registion successfully",
            'status' => 'Success',
            'token' => $token,
        ],201);
       }

    //    This Create Api For Login
       public function Login(Request $request){
            $request->validate([
                'email'=>'required|email',
                'password'=>'required',
            ]);
            $user = User::where('email',$request->email)->first();
            if($user && Hash::check($request->password, $user->password)){
                $token = $user->createToken($request->email)->plainTextToken;
                return response([
                    "message" => "Login Successfully",
                    "status"=>"success",
                    "token" =>$token,
                ],200);

            }
            return response([
                "message" => "The Incorrect authentication",
                "status" => "failed",
            ]);

       }
    //    This create api for Logout user

    public function Logout(Request $request){
        auth()->user()->tokens()->delete();
        return response([
           "message" => "Logout Successfullly",
           "status" => "Success",
        ],200);
    }
    public function Logged_data(){
        $userdata = auth()->user();
        return response([
            'message' => $userdata,
            'status' => "Success",
        ],200);
    }

    public function Change_Password(Request $request){
        $request->validate([
            'password' => 'required|confirmed',
        ]);
        $userpassword = auth()->user();
        $userpassword->password = Hash::make($request->password);
        $userpassword->save();
        return response([
            "message" => 'Susscessfully',
            "status" => 'success',
        ],200);

    }

}
