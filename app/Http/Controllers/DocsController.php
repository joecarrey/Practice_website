<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Query;
use App\Document;
use App\Help;  // trait 

class DocsController extends Controller
{
    use Help; // trait
    public function send_doc(Request $request){
        $valid = $this->validate_doc($request);
        if($valid)
            return $valid;

    	$doc = new Document;
    	$doc->query_id = $request->query_id;
        $doc->doc_file_1 = $this->save_file('doc_files', $request->file('doc_file_1'));
        $doc->doc_file_2 = $this->save_file('doc_files', $request->file('doc_file_2'));
        $doc->doc_file_3 = $this->save_file('doc_files', $request->file('doc_file_3'));
    	$doc->length = $request->length;
        $doc->object_type = $request->object_type;
        $doc->technology = $request->technology;
        $doc->region = $request->region;
        $doc->status = Query::STATUS_SENT;
        $doc->save();
        return back();
    }

    public function update_doc(Request $request, $id){
        $valid = $this->validate_doc($request);
        if($valid)
            return $valid;

    	$doc = Document::findOrFail($id);
    	if($doc->query()->user_id == Auth::user()->id){
    		$doc->doc_file_1 = $this->save_file('doc_files', $request->file('doc_file_1'), 1, $doc->doc_file_1);
	        $doc->doc_file_2 = $this->save_file('doc_files', $request->file('doc_file_2'), 1, $doc->doc_file_2);
	        $doc->doc_file_3 = $this->save_file('doc_files', $request->file('doc_file_3'), 1, $doc->doc_file_3);
	    	$doc->length = $request->length;
	        $doc->object_type = $request->object_type;
            $doc->technology = $request->technology;
	        $doc->region = $request->region;
	        $doc->status = Query::STATUS_SENT;
	        $doc->save();
            return back();
    	}
    }
}
