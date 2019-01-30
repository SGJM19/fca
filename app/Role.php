<?php 

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

	protected $connection = 'mysql2';
	public function __construct(){
		
	}
	public function users(){
		return $this->belongsToMany('App\User', 'role_user', 'user_id', 'role_id');
	}

	public function permissions(){
		return $this->belongsToMany('App\Permission', 'permission_role', 'role_id', 'permission_id');
	}

}