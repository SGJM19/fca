<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LimitAccess extends Model
{
    protected $table="limit_acess";
   	protected $fillable = ['user_id', 'company', 'company_code', 'month', 'num_of_days', 'limit_year', 'created_at', 'updated_at'];
   	public function User(){
   		return $this->belongsTo('App\User','user_id','id');
   	}
}
