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
}
