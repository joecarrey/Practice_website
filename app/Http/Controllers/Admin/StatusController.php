<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Query;
use App\Document;

class StatusController extends Controller
{
    public function query_status(Request $request, $id){
    	$query = Query::findOrFail($id);
    	$query->status = $request->status;
    	$query->save();
        return back(); 
    }

    public function doc_status(Request $request, $id){
    	$query = Document::findOrFail($id);
    	$query->status = $request->status;
    	$query->save();
        return back(); 
    }
}
