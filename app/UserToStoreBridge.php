<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToStoreBridge extends Model
{	

		protected $table = 'user_to_store_bridge';
		protected $connection = 'mysql2';
		public function User(){
			return $this->belongsTo('App\User','user_id','id');
		}
		public function Stores(){
			return $this->belongsTo('App\Stores','store_id','id');
		}
		
		protected $fillable = [
			'user_name', 'user_id', 'store_id', 'store_id_name', 'is_active'
		];
}
