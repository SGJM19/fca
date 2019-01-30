<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\User;
use App\Role;
use App\Permission;
use Auth;
use Illuminate\Support\Facades\Crypt;
use App\UserToStoreBridge;

class UserController extends Controller
{
   	public function __construct(){
   		$this->middleware('auth:api');
   	}

   	public function auth(){
        $response = [];
        $user = User::where('id',Auth::id())->get();
        foreach ($user as $val) {
            $response = array(
                'user_id'=> Crypt::encryptString(Auth::id()),
                'name'=>$val->name,
                'last_name'=>$val->last_name,
                'contact'=>$val->contact,
                'username'=>$val->username,
                'email'=>$val->email,
                'profile_image'=>$val->profile_image,
                'remember_token'=>$val->remember_token,
                'status'=>$val->status,
                'roles'=>$val->roles()->pluck('name')->toArray(),
                'permissions'=> [],
                'status'=> true
            );
            foreach ($val->roles as $val_res) {
                $response['permissions'] = $val_res->permissions()->pluck('name')->toArray();
            }
        }
        return response()->json($response);
    }

    public function checkIfAuthenticated(){
        $response = [];
         $stores = UserToStoreBridge::select([
                    DB::raw('store_id_name as restaurant_number')
                ])
                ->where('user_id',Auth::user()->id)
                ->whereDoesntHave('stores', function($q){
                    $q->where('is_active',0);
                });
                if(!empty($store) && $store !== 'all_stores'){
                    $stores->where('store_id_name',$store);
                }
                $stores = $stores->where('user_id',Auth::user()->id)
                ->groupBy('store_id_name')
                ->orderBy($sortBy, $descending)
                ->skip($offset)
                ->take($rowsPerPage)
                ->get();

        return response()->json($response);
    }

    public function getUserStores(){

    }

    public function test(){
 			return 'wew';   	
    }
}
