<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Mail;
use Illuminate\Support\Str;


class RegisterController extends Controller
{
	public function create(Request $request){
		$validator = Validator::make($request->all(), [
    		'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
    	]);
    	if($validator->fails()){
    		return response()->json($validator->errors(), 400);
    	}
	    $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $link = Str::random(30);
        DB::table('user_activations')->insert(['user_id'=>$user->id,'token'=>$link]);
        $temp = json_decode($user, true);
        $temp['link'] = $link;
        Mail::send('mail.sendmail', $temp, function($message) use ($temp){
          $message->to($temp['email']);
          $message->subject('Activation Code');
        });
        // return $user;
        return response()->json(['user'=>$user, 'message'=>'We sent activation code. Please check your email.', 'code' => 201], 201);
    }

    public function userActivation($token){
        $check = DB::table('user_activations')->where('token',$token)->first();
        if(!is_null($check)){
            $user = User::find($check->user_id);
            if ($user->is_activated == 1){
                return response()->json(['message'=>'User is already activated', 'code' => 205], 205);
            }
            $user->is_activated = 1;
            $user->save();
            DB::table('user_activations')->where('token',$token)->delete();
            // return response()->json(['message'=>'Your accaunt has been activated.', 'code' => 200], 200);
            return redirect()->route('home');
        }
        return response()->json(['message'=>'Token is invalid!', 'code' => 400], 400);
    }    
}
