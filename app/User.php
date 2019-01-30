<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, EntrustUserTrait;
    protected $connection = "mysql2";

    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function Fca(){
        return $this->hasMany('App\Fca','user_id','id');
    }

    public function FcaComments(){
        return $this->hasMany('App\FcaComments','fca_id','id');
    }

    public function FcaSettingsModel(){
        return $this->hasMany('App\FcaSettingsModel','user_id','id');
    }

    public function LimitAcccess(){
        return $this->hasMany('App\LimitAcccess','user_id','id');
    }

    protected $hidden = [
        'password', 'remember_token',
    ];
}
