<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function view_file($filename, $folder){
    	$p = "app\\public\\" . $folder . "\\" . $filename;
		$path = storage_path($p);
		return response()->download($path, $filename, [], 'inline');
    }
}
