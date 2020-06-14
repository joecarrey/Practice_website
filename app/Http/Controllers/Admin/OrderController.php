<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Order;
use App\Help; // trait
use Validator;

class OrderController extends Controller
{
	use Help; // trait
    public function create_order(Request $request, $id){
    	$valid = $this->validate_order($request);
    	if($valid)
    		return $valid;
    	
    	$order = new Order;
    	$order->query_id = $id;
    	$order->order_file = $this->save_file('order_files', $request->file('order_file'), 1);
    	$order->save();
        return back();
    }

    public function update_order(Request $request, $id){
    	$valid = $this->validate_order($request);
    	if($valid)
    		return $valid;
    	
    	$order = Order::findOrFail($id);
    	$order->order_file = $this->save_file('order_files', $request->file('order_file'), 1, 1, $order->order_file);
    	$order->save();
        return back();
    }

    public function delete($id){
    	$order = Order::findOrFail($id);
		$order->delete();
        return back();
	}
}
