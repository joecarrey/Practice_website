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
		$result = $this->create_comment($request, $id, $comment, 0);
		if($result)
			return $result;
	}

	public function comment_doc(Request $request, $id){
		$comment = new Doc_Comment;
		$result = $this->create_comment($request, $id, $comment, 1);
		if($result)
			return $result;
	}


	public function update_query_comment(Request $request, $id){
		$comment = Query_Comment::findOrFail($id);
		$result = $this->update_comment($request, $comment);
		if($result)
			return $result;
	}

	public function update_doc_comment(Request $request, $id){
		$comment = Doc_Comment::findOrFail($id);
		$result = $this->update_comment($request, $comment);
		if($result)
			return $result;
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
	}
}
