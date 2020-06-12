<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Hash;
use Illuminate\Support\Str;
use App\Admin;
use App\User;
use DB;
use Mail;


class LoginController extends Controller
{
	use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(){
    	return view('admin.login');
    }
    
    public function dashboard()
    {
    	session_start();
    	if((isset($_SESSION['token'])) and (Admin::where('name', $_SESSION['name'])->first()->remember_token == $_SESSION['token']))
        	return view('admin.dashboard');
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