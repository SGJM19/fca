<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    protected $table="stores";
    protected $connection = "mysql2";

    public function UserToStoreBridge(){
    	return $this->hasMany('App\UserToStoreBridge','store_id','id');
    }

    public function users(){
    	return $this->belongsToMany('App\User','db_cua.user_to_store_bridge','store_id','user_id');
    }

    public function Fca(){
    	return $this->hasMany('App\Fca','store_id','id');
    }

    public function ExemptStore(){
        return $this->hasMany('App\ExemptStore', 'store_id', 'id');
    }


}
