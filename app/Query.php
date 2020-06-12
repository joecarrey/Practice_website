<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
	const STATUS_SENT 				= 1;
    const STATUS_RECEIVED			= 2;
    const STATUS_FINISHED           = 3;
    const STATUS_SENT_TO_PROCESS	= -1;
    const STATUS_PROCESS            = -2;

    public function user(){
    	return $this->belongsTo('App\User');
    }

    public function query_comment(){
    	return $this->hasMany('App\Query_Comment');
    }
}
