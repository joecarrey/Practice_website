<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use App\Providers\RouteServiceProvider;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use Hash;
use Validator;
use App\Admin;
use App\User;
use App\Query;
use App\Document;

class LoginController extends Controller
{
    public function index(){
    	return view('admin.login');
    }
    
    public function dashboard()
    {
    	session_start();
    	if((isset($_SESSION['token'])) and (Admin::where('name', $_SESSION['name'])->first()->remember_token == $_SESSION['token'])){

            // $queries = Query::all()->map(function ($query) {
            //     $query->query_comment;
            //     $query->user;
            //     $query->order;
            //     return $query;
            // });
            $queries = Query::with('order')->with('user')->with('query_comment')->get();
            $documents = Document::with('queryabc')->with('user')->with('doc_comment')->get();
        	return view('admin.dashboard')->with('queries', $queries)->with('documents', $documents);
        }
        else
        	return redirect()->route('admin_login');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'password' => 'required|min:8',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 401);
        }

    	$admin= Admin::where('name',$request->name)->first();
		if(!$admin){
			return response(['message'=>'User not found or not activated', 'code'=> 404], 404);
		}

		if(Hash::check($request->password, $admin->password)){
			$token = Str::random(30);
			
			$admin->remember_token = $token;
			$admin->save();

			session_start();
    		$_SESSION['name'] = $admin->name;
    		$_SESSION['token'] = $token;
			// return redirect()->route('dashboard')->with('name', $admin->name)->with('token', $token);
			return redirect()->route('dashboard');

		}
    }

    public function logout(){
    	session_start();
		unset($_SESSION['token']);
		return redirect()->route('admin_login');
    }
}