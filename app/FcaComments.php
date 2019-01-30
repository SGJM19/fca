<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FcaComments extends Model
{
    protected $table="tbl_fca_comment";
    protected $fillable=['comments','fca_id','user_id','isDeleted','isRead'];
    public function Fca(){
    	return $this->belongsTo('App\Fca','fca_id','id');
    }

    public function User(){
    	return $this->belongsTo('User\App','user_id','id');
    }
}
