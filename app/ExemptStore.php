<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExemptStore extends Model
{  
    protected $table = "exempt_store";
    protected $connection = "mysql";
    protected $fillable = ['store_id', 'store_name', 'month_num', 'month_txt'];
    public function Stores()
    {
        return $this->belongsTo('App\Stores', 'store_id', 'id');
    }   
}
