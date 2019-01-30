<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FcaExpSettings extends Model
{
		protected $table="tbl_fca_settings1";
		protected $fillable = ['default_expiration_days'];

		
}
