<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function send_rest_passwoed_email(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        // Check User's email Exists or Not
        $user = User::where('email',$email)->first();
        if(!$user){
            return response([
                'message' => 'Email doesnt exists',
                'status' => 'failed',
            ],404);
        }

        // Genrate Token
         $token = Str::random(60);

        // Saving data to password reset tables
        PasswordReset::create([
            'email'=>$email,
            'token'=>$token,
            'created_at' => Carbon::now()
        ]);


        // dump("http://127.0.0.1:8000/api/user/reset/" . $token);

        // Sendeing Email with Password Reset View
        Mail::send('reset_email',['token'=>$token],function(Message $message)use($email){
            $message->subject('Reset Your Password');
            $message->to($email);
        });

        return response([
            'message'=>'Password Reset Email Sent... Ceck Your Email',
            'status' => 'success',
        ],200);
    }

    public  function Resest(Request $request, $token){
        // Deleting token older then 1 minuts
        $formatted = Carbon::now()->subMinutes(1)->toDateTimeString();
        PasswordReset::where('created_at','<=',$formatted)->delete();
        $request->validate([
            'password'=>'required|confirmed',
        ]);
        $passwordreset = PasswordReset::where('token',$token)->first();
        if(!$passwordreset){
            return response([
                'message' => 'Token is Invalid or Expired',
                'status' => 'failed',
            ],404);
        }
        $user = User::where('email',$passwordreset->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        PasswordReset::where('email',$user->email)->delete();

        return response([
            'message'=>'Password Reset Successfully',
            'status' => 'Success'
        ],200);

    }
}
