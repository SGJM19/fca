<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FcaFiles extends Model
{
    protected $table="tbl_fca_files";
    protected $fillable = ['fca_id', 'file_name', 'file_size', 'file_path', 'file_ext', 'orginal_file_name'];
    
    public function Fca(){
    	return $this->belongsTo('App\Fca','fca_id','id');
    }


}
