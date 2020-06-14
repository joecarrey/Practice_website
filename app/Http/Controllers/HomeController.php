<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Query;
use App\Document;

class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function panel()
    {
        $queries = Query::where('user_id', Auth::user()->id)->get()->map(function ($query) {
                $query->query_comment;
                return $query;
            });
        $documents = Document::where('user_id', Auth::user()->id)->get()->map(function ($doc) {
                $doc->queryabc;
                $doc->doc_comment;
                return $doc;
            });
        // return md5(uniqid(1, true));
        // $queries = Query::where('user_id', Auth::user()->id)->get();
        return view('panel')->with('queries', $queries)->with('documents', $documents);
    }
}