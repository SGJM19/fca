<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FcaOldRecord extends Model
{
    protected $table ="tbl_fca_old_record";
    protected $fillable = ['fca_id','store_id','percentage','audit_by_name'];
    public function Fca(){
    	return $this->belongsTo('App\Fca', 'fca_id','id');
    }
}
