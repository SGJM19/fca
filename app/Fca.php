<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fca extends Model
{
		protected $table="tbl_fca";
	 	protected $fillable = ['store_id','store', 'user_id', 'year_filed', 'month_filed_num', 'month_filed_string', 'percentage', 'remarks', 'expiration_date', 'file_id', 'audit_by','audit_date','isPassed','isEmailed','isReeval','isRead'];
	  public function Users(){
	  	return $this->belongsTo('App/User','user_id','id');
	  }

	  public function FcaFiles(){
	  	return $this->hasMany('App\FcaFiles', 'fca_id','id');
	  }

	  public function FcaComments(){
	  	return $this->hasMany('App\FcaComments','fca_id','id');
	  }

	  public function Stores(){
	  	return $this->belongsTo('App\Stores','store_id', 'id');
	  }

	  public function FcaOldRecord(){
	  	return $this->belongsTo('App\FcaOldRecord','fca_id','id');
	  }

}
