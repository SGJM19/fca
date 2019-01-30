<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\FcaSettingsModel;
use App\FcaExpSettings;
use App\LimitAccess;
use Auth;
use Illuminate\Support\Facades\DB;

class FcaSettings extends Controller
{
    public function __construct(){
    	$this->middleware('auth:api');
    }
    public function getArls(Request $request){
    	$response = [];

    	$dataset = [];

    	$arls = User::whereHas('roles',function($q){
    		$q->where('name','arl_am');
    	})
    	->get();

    	foreach ($arls as $key => $value) {
            $settings  = FcaSettingsModel::where('user_id',$value->id)
                                         ->where('isAllowed', 1)
                                         ->get()
                                         ->pluck('month')
                                         ->toArray();
    		$dataset[] = array(
    			'arl_name'=>$value->name. ' ' . $value->last_name,
    			'status'=> $settings,
    			'user_id'=>$value->id,
    			'emial'=>$value->email
    		);
    	}
    	$response['data'] = $dataset;
    	return response()->json($response);
    }

    public function set_arl_settings(Request $request){
        $response = [];
        $user_id = $request->user_id;
        $month = $request->month;
        if(!empty($request->val)){
            $value  =1;
        }else{
            $value = 0;
        }
        $store = FcaSettingsModel::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'month'=>$month
                ],
                [
                    'isAllowed'=> $value,
                    
                ]
            );
        if($store){

        }

        return response()->json($response);
    }


    public function getCurrentMonths(Request $request){
        $response = [];
        $FcaSettingsModel = FcaSettingsModel::where('user_id',$request->user_id)
                                            ->where('isAllowed',1)
                                            ->get()->pluck('month')->toArray();
        $response['data'] = $FcaSettingsModel;
        return response()->json($response);
    }

    public function saveExpSettings(Request $request){
        $response = [];
    
        $update = FcaExpSettings::updateOrCreate(
            [
                'default_shit'=>1
            ],
            [
                'default_expiration_days'=>$request->days
            ]
        );

        return response()->json($response);
    }

    public function store_threshold_percent(Request $request){
        $response = [];
        if(!empty($request->percentage)){
            $update = FcaExpSettings::updateOrCreate(
                [
                    'default_shit'=>0
                ],
                [
                    'default_expiration_days'=>$request->percentage
                ]
            );
        }
        return response()->json($response);
    }

    public function get_store_threshold_percent(){
        $response = [];

        $get = FcaExpSettings::where('id',3)->first();

        return response()->json($response);
    }

    public function getCurrentExpSettings(){
        $response = [];

        $get = FcaExpSettings::where('id',2)->first();
        $get_percent = FcaExpSettings::where('id',3)->first();

        //pasingit d2
        #ofi
        $hfi_item = LimitAccess::select([
                        DB::raw('month as id'),
                        DB::raw('num_of_days as value')
                    ])->where('company_code',1)->get();
        $ofi_item = LimitAccess::select([
                        DB::raw('month as id'),
                        DB::raw('num_of_days as value')
                    ])->where('company_code',2)->get();

        $response['hfi_item'] = $hfi_item;
        $response['ofi_item'] = $ofi_item;
        $response['data'] = $get->default_expiration_days;
        $response['percentage'] = $get_percent->default_expiration_days;
        return response()->json($response);
    }

    public function get_users(Request $request){
        $response = [];
        $user = User::has('roles')->with(['roles'])->get();

        $getFcasettings = FcaSettingsModel::all()->pluck('user_id')->toArray();
        /*get only user with roles*/
        $response['data'] = $user;
        $response['data_upload'] = $getFcasettings;
        return response()->json($response);
    }
    public function store_email_to(Request $request){
        $response = [];
        $user_id = $request->user_id;
        $isRemove = $request->isRemove;
        //echo $isRemove;
        /*getUser details*/
        if($isRemove == 0){
            $insert = FcaSettingsModel::updateOrCreate([
                        'user_id'=>$user_id
                    ]);
            if($insert){
                $response['data'] = 'success';
            }
        }else{

            $delete = FcaSettingsModel::where('user_id',$user_id)->delete();
            if($delete){
                $response['data'] = 'success';
            }
        }

        return response()->json($response);
    }

    public function saveAndUpdateAccessSettings(Request $request){
        $response = [];
        $ofi = $request->ofi_item;
        $hfi = $request->hfi_item;
        
        foreach ($ofi as $key => $value) { //for ofi
            $update = LimitAccess::updateOrCreate(
                    [
                        'company'=>'ofi',
                        'company_code'=>2,
                        'month'=>$value['id']
                    ],
                    [
                        'user_id'=>Auth::user()->id,
                        'num_of_days'=>$value['value']
                    ]
                );
        }

        foreach ($hfi as $key => $value) { //for ofi
            $update = LimitAccess::updateOrCreate(
                    [
                        'company'=>'hfi',
                        'company_code'=>1,
                        'month'=>$value['id']
                    ],
                    [
                        'user_id'=>Auth::user()->id,
                        'num_of_days'=>$value['value']
                    ]
                );
        }


        

        return response()->json($response);
    }
}
