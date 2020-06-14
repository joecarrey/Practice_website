<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function user(){
    	return $this->belongsTo('App\User', 'user_id');
    }
    
    public function queryabc(){
    	return $this->belongsTo('App\Query', 'query_id');
    }

    public function doc_comment(){
    	return $this->hasOne('App\Doc_Comment', 'doc_id');
    }
}
