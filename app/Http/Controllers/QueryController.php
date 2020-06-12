<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Query;
use App\Help;  // trait 

class QueryController extends Controller
{
	use Help; // trait
    public function send_query(Request $request){
        $valid = $this->validate_query($request);
        if($valid)
            return $valid;

        $query = new Query;
        $query->number_of_stations = $request->number_of_stations;
        $query->description = $request->description;
        $query->query_file_1 = $this->save_file('query_files', $request->file('query_file_1'));
        $query->query_file_2 = $this->save_file('query_files', $request->file('query_file_2'));
        $query->status = Query::STATUS_SENT;
        $query->user_id = Auth::user()->id;
        $query->save();
        return back();
    }

    public function update_query(Request $request, $id){
        $valid = $this->validate_query($request);
        if($valid)
         return $valid;
    
        $query = Query::findOrFail($id);
        if(($query->user_id == Auth::user()->id) and ($query->status != Query::STATUS_FINISHED)){
            $query->number_of_stations = $request->number_of_stations;
            $query->description = $request->description;
            $query->query_file_1 = $this->save_file('query_files', $request->file('query_file_1'), 1, $query->query_file_1);
	        $query->query_file_2 = $this->save_file('query_files', $request->file('query_file_2'), 1, $query->query_file_2);
	        $query->status = Query::STATUS_SENT;
	        $query->save();
            return back();
    	}
    }
}
