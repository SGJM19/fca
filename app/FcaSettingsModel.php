<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FcaSettingsModel extends Model
{
    protected $table = "tbl_fca_settings";
   	protected $fillable = ['user_id', 'isAllowed', 'month', 'deadline'];
    public function User(){
    	return $this->belongsTo('App\User', 'user_id','id');
    }
}
