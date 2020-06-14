<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Query_Comment;
use App\Doc_Comment;
use App\Help; // trait

class CommentController extends Controller
{
	use Help; // trait
	public function comment_query(Request $request, $id){
		$comment = new Query_Comment;
		$result = $this->validate_comment($request);
		if($result)
			return back()->with('error', $result);
		$comment->query_id = $id;
    	$comment->body = $request->body;
    	$comment->save();
		return back();
	}

	public function comment_doc(Request $request, $id){
		$comment = new Doc_Comment;
		$result = $this->validate_comment($request);
		if($result)
			return back()->with('error', $result);
		$comment->doc_id = $id;
    	$comment->body = $request->body;
    	$comment->save();
		return back();
	}


	public function update_query_comment(Request $request, $id){
		$comment = Query_Comment::findOrFail($id);
		$result = $this->validate_comment($request);
		if($result)
			return back()->with('error', $result);
		$comment->body = $request->body;
        $comment->save();
		return back();
	}

	public function update_doc_comment(Request $request, $id){
		$comment = Doc_Comment::findOrFail($id);
		$result = $this->validate_comment($request);
		if($result)
			return back()->with('error', $result);
		$comment->body = $request->body;
        $comment->save();
		return back();
	}

	/**
     * Create comment for queries and documents
     *
     * @param  $type: 0 - query, 1 - docs
     * 
     */
	public function delete($id, $type){
		if($type == 0)
			$comment = Query_Comment::findOrFail($id);
		else
			$comment = Doc_Comment::findOrFail($id);
		$comment->delete();
		return back();
	}
}
