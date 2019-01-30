<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $connection = 'mysql2';
		public function roles(){
			return $this->belongsToMany('App\Role', 'permission_role', 'role_id', 'permission_id');
		}
}
