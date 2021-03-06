<?php

namespace App;
use Validator;
trait Help
{
    public function save_file($folder, $file, $i, $update = null, $old = null){
    	if($update){
	        $split = explode("/", $old);
	        $filename = end($split);
	        $del = "app\\public\\" . $folder . "\\" . $filename;

	        if($filename != null){
	            unlink(storage_path($del));
	        }
	    }
    	
		// get filename with extension
	    $filenameWithExt = $file->getClientOriginalName();
	    
	    //get just filename
	    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

	    // get just ext
	    $extension = $file->getClientOriginalExtension();

	    //filename to store
	    $fileNameToStore = $filename.'_'.md5(uniqid($i, true)).'.'.$extension;

	    //upload image
	    $path = $file->storeAs('public/' . $folder, $fileNameToStore);

	    return $fileNameToStore; 
    }

    public function validate_comment($request){
        $rules = [
            'body' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        else
            return null;
    }

    public function validate_order($request)
    {
    	$rules = [
            'order_file' => 'required|mimes:pdf|max:5120',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        else
        	return null;
    }

    public function validate_query($request)
    {
    	$validator = Validator::make($request->all(), [
    		'number_of_stations' => 'required|integer',
    		'description' => 'required|string',
    		'query_file_1' => 'required|mimes:pdf|max:5120',
    		'query_file_2' => 'required|mimes:pdf|max:5120',
    	]);
    	if($validator->fails()){
    		return response()->json($validator->errors(), 401);
    	}
    	else
    		return null;
    }

    public function validate_doc($request)
    {
    	$validator = Validator::make($request->all(), [
    		'doc_file_1' => 'required|mimes:pdf|max:5120',
    		'doc_file_2' => 'required|mimes:pdf|max:5120',
    		'doc_file_3' => 'required|mimes:pdf|max:5120',
    		'length' => 'required|integer',
    		'object_type' => 'required|string',
            'technology' => 'required|string',
    		'region' => 'required|string',
    	]);
    	if($validator->fails()){
    		return response()->json($validator->errors(), 401);
    	}
    	else
    		return null;
    }  
}