<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Fca;
use Auth;
use App\UserToStoreBridge;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\FcaFiles;
use App\FcaExpSettings;
use App\FcaSettingsModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Mail;
use Excel;
use App\FcaOldRecord;
use App\LimitAccess;
use App\ExemptStore;
use App\Stores;


class FcaController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function addYear(Request $request){
        $response = [];
        $stores = $this->get_stores();
        if(!empty($request->store_id)){
            $store_id = $request->store_id;
        }else{
            $store_id = $stores[0];
        }

        if(empty($store_id) || empty($request->year)){
            $response['status'] = false;
        }else{
            $fca = Fca::updateOrCreate(
                    [
                        'user_id'=>Auth::user()->id,
                        'store_id'=>$store_id,
                        'year_filed'=>$request->year,
                        'store' => $request->store_name
                    ]
                );
            if($fca->wasRecentlyCreated){
                $response['status'] = true;
            }else{
                $response['status'] = false;
            }
        } 
        return response()->json($response);
    }

    public function get_fca_table(Request $request){
        $response = [];
        $stores = $this->get_stores();
        $dataset = [];

        if(!empty($request->year)){
            $year = $request->year;
        }else{
            $year = Carbon::now()->format('Y');
        }
        $store_id = $request->store_id;
        $store_name = $request->store_name;

        foreach ($stores as $key => $value) {
            /*PUT data inside*/
                $Jan = '';
                $Feb = '';
                $Mar = '';
                $Apr = '';
                $May = '';
                $Jun = '';
                $Jul = '';
                $Aug = '';
                $Sep = '';
                $Oct = '';
                $Nov = '';
                $Dec = '';

            /*Ispassed*/
                $Jan_ispassed = '';
                $Feb_ispassed = '';
                $Mar_ispassed = '';
                $Apr_ispassed = '';
                $May_ispassed = '';
                $Jun_ispassed = '';
                $Jul_ispassed = '';
                $Aug_ispassed = '';
                $Sep_ispassed = '';
                $Oct_ispassed = '';
                $Nov_ispassed = '';
                $Dec_ispassed = '';

            /*Ispassed*/
                $Jan_reeval = '';
                $Feb_reeval = '';
                $Mar_reeval = '';
                $Apr_reeval = '';
                $May_reeval = '';
                $Jun_reeval = '';
                $Jul_reeval = '';
                $Aug_reeval = '';
                $Sep_reeval = '';
                $Oct_reeval = '';
                $Nov_reeval = '';
                $Dec_reeval = '';

            /*Audit date*/
                $Jan_ad = '';
                $Feb_ad = '';
                $Mar_ad = '';
                $Apr_ad = '';
                $May_ad = '';
                $Jun_ad = '';
                $Jul_ad = '';
                $Aug_ad = '';
                $Sep_ad = '';
                $Oct_ad = '';
                $Nov_ad = '';
                $Dec_ad = '';

            /*Audit date*/
                $Jan_ad_by = '';
                $Feb_ad_by = '';
                $Mar_ad_by = '';
                $Apr_ad_by = '';
                $May_ad_by = '';
                $Jun_ad_by = '';
                $Jul_ad_by = '';
                $Aug_ad_by = '';
                $Sep_ad_by = '';
                $Oct_ad_by = '';
                $Nov_ad_by = '';
                $Dec_ad_by = '';



            /*PUT data inside*/
                $Jan_remarks = '';
                $Feb_remarks = '';
                $Mar_remarks = '';
                $Apr_remarks = '';
                $May_remarks = '';
                $Jun_remarks = '';
                $Jul_remarks = '';
                $Aug_remarks = '';
                $Sep_remarks = '';
                $Oct_remarks = '';
                $Nov_remarks = '';
                $Dec_remarks = '';

            /*Expiration variable*/
                $Jan_exp = '';
                $Feb_exp = '';
                $Mar_exp = '';
                $Apr_exp = '';
                $May_exp = '';
                $Jun_exp = '';
                $Jul_exp = '';
                $Aug_exp = '';
                $Sep_exp = '';
                $Oct_exp = '';
                $Nov_exp = '';
                $Dec_exp = '';


            /*get percebtage */
                $Jan_percent = '';
                $Feb_percent = '';
                $Mar_percent = '';
                $Apr_percent = '';
                $May_percent = '';
                $Jun_percent = '';
                $Jul_percent = '';
                $Aug_percent = '';
                $Sep_percent = '';
                $Oct_percent = '';
                $Nov_percent = '';
                $Dec_percent = '';

            /*get ID*/
                $Jan_id = '';
                $Feb_id = '';
                $Mar_id = '';
                $Apr_id = '';
                $May_id = '';
                $Jun_id = '';
                $Jul_id = '';
                $Aug_id = '';
                $Sep_id = '';
                $Oct_id = '';
                $Nov_id = '';
                $Dec_id = '';

            /*For january*/
            $jan_query = $this->checkIfDataExists($value['value'],$value['text'],$year,1)->toArray();
            if(count($jan_query) > 0){
                $Jan = 1;
                $Jan_id = $jan_query[0]['id'];
                $Jan_exp = $jan_query[0]['expiration_date'];
                $Jan_percent = $jan_query[0]['percentage'];
                $Jan_remarks = $jan_query[0]['remarks'];
                $Jan_ad = (!empty($jan_query[0]['audit_date']) ? date('Y-m-d',strtotime($jan_query[0]['audit_date'])) : 0);
                $Jan_ad_by = $jan_query[0]['audit_by'];
                $Jan_ispassed = $jan_query[0]['isPassed'];
                $Jan_reeval = $jan_query[0]['isReeval'];
            }   

            $feb_query = $this->checkIfDataExists($value['value'],$value['text'],$year,2)->toArray();
            if(count($feb_query) > 0){
                $Feb = 2;
                $Feb_id = $feb_query[0]['id'];
                $Feb_exp = $feb_query[0]['expiration_date'];
                $Feb_percent = $feb_query[0]['percentage'];
                $Feb_remarks = $feb_query[0]['remarks'];
                $Feb_ad = date('Y-m-d',strtotime($feb_query[0]['audit_date']));
                $Feb_ad_by = $feb_query[0]['audit_by'];
                $Feb_ispassed = $feb_query[0]['isPassed'];
                $Feb_reeval = $feb_query[0]['isReeval'];
            }

            $mar_query = $this->checkIfDataExists($value['value'],$value['text'],$year,3)->toArray();
            if(count($mar_query) > 0){
                $Mar = 3;
                $Mar_id = $mar_query[0]['id'];
                $Mar_exp = $mar_query[0]['expiration_date'];
                $Mar_percent = $mar_query[0]['percentage'];
                $Mar_remarks = $mar_query[0]['remarks'];
                $Mar_ad = date('Y-m-d',strtotime($mar_query[0]['audit_date']));
                $Mar_ad_by = $mar_query[0]['audit_by'];
                $Mar_ispassed = $mar_query[0]['isPassed'];
                $Mar_reeval = $mar_query[0]['isReeval'];
            }
            $arp_query = $this->checkIfDataExists($value['value'],$value['text'],$year,4)->toArray();
            if(count($arp_query) > 0){
                $Apr = 4;
                $Apr_id = $arp_query[0]['id'];
                $Apr_exp = $arp_query[0]['expiration_date'];
                $Apr_percent = $arp_query[0]['percentage'];
                $Apr_remarks = $arp_query[0]['remarks'];
                $Apr_ad = (!empty($arp_query[0]['audit_date']) ? date('Y-m-d',strtotime($arp_query[0]['audit_date'])): 0);
                $Apr_ad_by = $arp_query[0]['audit_by'];
                $Apr_ispassed = $arp_query[0]['isPassed'];
                $Apr_reeval = $arp_query[0]['isReeval'];
            }

            $may_query = $this->checkIfDataExists($value['value'],$value['text'],$year,5)->toArray();
            if(count($may_query) > 0){
                $May = 5;
                $May_id = $may_query[0]['id'];
                $May_exp = $may_query[0]['expiration_date'];
                $May_percent = $may_query[0]['percentage'];
                $May_remarks = $may_query[0]['remarks'];
                $May_ad = date('Y-m-d',strtotime($may_query[0]['audit_date']));
                $May_ad_by = $may_query[0]['audit_by'];
                $May_ispassed = $may_query[0]['isPassed'];
                $May_reeval = $may_query[0]['isReeval'];
            } 

            $jun_query = $this->checkIfDataExists($value['value'],$value['text'],$year,6)->toArray();
            if(count($jun_query) > 0){
                $Jun = 6;
                $Jun_id = $jun_query[0]['id'];
                $Jun_exp = $jun_query[0]['expiration_date'];
                $Jun_percent = $jun_query[0]['percentage'];
                $Jun_remarks = $jun_query[0]['remarks'];
                $Jun_ad = date('Y-m-d',strtotime($jun_query[0]['audit_date']));
                $Jun_ad_by = $jun_query[0]['audit_by'];
                $Jun_ispassed = $jun_query[0]['isPassed'];
                $Jun_reeval = $jun_query[0]['isReeval'];
            } 

            $jul_query = $this->checkIfDataExists($value['value'],$value['text'],$year,7)->toArray();
            if(count($jul_query) > 0){
                $Jul = 7;
                $Jul_id = $jul_query[0]['id'];
                $Jul_exp = $jul_query[0]['expiration_date'];
                $Jul_percent = $jul_query[0]['percentage'];
                $Jul_remarks = $jul_query[0]['remarks'];
                $Jul_ad = date('Y-m-d',strtotime($jul_query[0]['audit_date']));
                $Jul_ad_by = $jul_query[0]['audit_by'];
                $Jul_ispassed = $jul_query[0]['isPassed'];
                $Jul_reeval = $jul_query[0]['isReeval'];
            } 

            $aug_query = $this->checkIfDataExists($value['value'],$value['text'],$year,8)->toArray();
            if(count($aug_query) > 0){
                $Aug = 8;
                $Aug_id = $aug_query[0]['id'];
                $Aug_exp = $aug_query[0]['expiration_date'];
                $Aug_percent = $aug_query[0]['percentage'];
                $Aug_remarks = $aug_query[0]['remarks'];
                $Aug_ad = date('Y-m-d',strtotime($aug_query[0]['audit_date']));
                $Aug_ad_by = $aug_query[0]['audit_by'];
                $Aug_ispassed = $aug_query[0]['isPassed'];
                $Aug_reeval = $aug_query[0]['isReeval'];
            } 

            $sep_query = $this->checkIfDataExists($value['value'],$value['text'],$year,9)->toArray();
            if(count($sep_query) > 0){
                $Sep = 9;
                $Sep_id = $sep_query[0]['id'];
                $Sep_exp = $sep_query[0]['expiration_date'];
                $Sep_percent = $sep_query[0]['percentage'];
                $Sep_remarks = $sep_query[0]['remarks'];
                $Sep_ad = date('Y-m-d',strtotime($sep_query[0]['audit_date']));
                $Sep_ad_by = $sep_query[0]['audit_by'];
                $Sep_ispassed = $sep_query[0]['isPassed'];
                $Sep_reeval = $sep_query[0]['isReeval'];
            } 

            $oct_query = $this->checkIfDataExists($value['value'],$value['text'],$year,10)->toArray();
            if(count($oct_query) > 0){
                $Oct = 10;
                $Oct_id = $oct_query[0]['id'];
                $Oct_exp = $oct_query[0]['expiration_date'];
                $Oct_percent = $oct_query[0]['percentage'];
                $Oct_remarks = $oct_query[0]['remarks'];
                $Oct_ad = date('Y-m-d',strtotime($oct_query[0]['audit_date']));
                $Oct_ad_by = $oct_query[0]['audit_by'];
                $Oct_ispassed = $oct_query[0]['isPassed'];
                $Oct_reeval = $oct_query[0]['isReeval'];
            } 

            $nov_query = $this->checkIfDataExists($value['value'],$value['text'],$year,11)->toArray();
            if(count($nov_query) > 0){
                $Nov = 10;
                $Nov_id = $nov_query[0]['id'];
                $Nov_exp = $nov_query[0]['expiration_date'];
                $Nov_percent = $nov_query[0]['percentage'];
                $Nov_remarks = $nov_query[0]['remarks'];
                $Nov_ad = date('Y-m-d',strtotime($nov_query[0]['audit_date']));
                $Nov_ad_by = $nov_query[0]['audit_by'];
                $Nov_ispassed = $nov_query[0]['isPassed'];
                $Nov_reeval = $nov_query[0]['isReeval'];
            } 

            $dec_query = $this->checkIfDataExists($value['value'],$value['text'],$year,12)->toArray();
            if(count($dec_query) > 0){
                $Dec = 12;
                $Dec_id = $dec_query[0]['id'];
                $Dec_exp = $dec_query[0]['expiration_date'];
                $Dec_percent = $dec_query[0]['percentage'];
                $Dec_remarks = $dec_query[0]['remarks'];
                $Dec_ad = date('Y-m-d',strtotime($dec_query[0]['audit_date']));;
                $Dec_ad_by = $dec_query[0]['audit_by'];
                $Dec_ispassed = $dec_query[0]['isPassed'];
                $Dec_reeval = $dec_query[0]['isReeval'];
            } 



            $dataset[] = [
                'year' => $year,
                'store_id_real'=> $value['value'],
                'store_id' => $value['text'],
                'company_code' => $value['company'],                


                /*Remarks*/
                    'Jan_remarks' => $Jan_remarks,
                    'Feb_remarks' => $Feb_remarks,
                    'Mar_remarks' => $Mar_remarks,
                    'Apr_remarks' => $Apr_remarks, 
                    'May_remarks' => $May_remarks, 
                    'Jun_remarks' => $Jun_remarks, 
                    'Jul_remarks' => $Jul_remarks, 
                    'Aug_remarks' => $Aug_remarks, 
                    'Sep_remarks' => $Sep_remarks, 
                    'Oct_remarks' => $Oct_remarks, 
                    'Nov_remarks' => $Nov_remarks, 
                    'Dec_remarks' => $Dec_remarks,

                /*isPssed*/
                    'Jan_ispassed'=> $Jan_ispassed,
                    'Feb_ispassed'=> $Feb_ispassed,
                    'Mar_ispassed'=> $Mar_ispassed,
                    'Apr_ispassed'=> $Apr_ispassed,
                    'May_ispassed'=> $May_ispassed,
                    'Jun_ispassed'=> $Jun_ispassed,
                    'Jul_ispassed'=> $Jul_ispassed,
                    'Aug_ispassed'=> $Aug_ispassed,
                    'Sep_ispassed'=> $Sep_ispassed,
                    'Oct_ispassed'=> $Oct_ispassed,
                    'Nov_ispassed'=> $Nov_ispassed,
                    'Dec_ispassed'=> $Dec_ispassed,

                /*isPssed*/
                    'Jan_reeval'=> $Jan_reeval,
                    'Feb_reeval'=> $Feb_reeval,
                    'Mar_reeval'=> $Mar_reeval,
                    'Apr_reeval'=> $Apr_reeval,
                    'May_reeval'=> $May_reeval,
                    'Jun_reeval'=> $Jun_reeval,
                    'Jul_reeval'=> $Jul_reeval,
                    'Aug_reeval'=> $Aug_reeval,
                    'Sep_reeval'=> $Sep_reeval,
                    'Oct_reeval'=> $Oct_reeval,
                    'Nov_reeval'=> $Nov_reeval,
                    'Dec_reeval'=> $Dec_reeval,


                /*Audit Date*/
                    'Jan_ad' => $Jan_ad,
                    'Feb_ad' => $Feb_ad,
                    'Mar_ad' => $Mar_ad,
                    'Apr_ad' => $Apr_ad, 
                    'May_ad' => $May_ad, 
                    'Jun_ad' => $Jun_ad, 
                    'Jul_ad' => $Jul_ad, 
                    'Aug_ad' => $Aug_ad, 
                    'Sep_ad' => $Sep_ad, 
                    'Oct_ad' => $Oct_ad, 
                    'Nov_ad' => $Nov_ad, 
                    'Dec_ad' => $Dec_ad,

                /*Audit By*/
                    'Jan_ad_by' => $Jan_ad_by,
                    'Feb_ad_by' => $Feb_ad_by,
                    'Mar_ad_by' => $Mar_ad_by,
                    'Apr_ad_by' => $Apr_ad_by, 
                    'May_ad_by' => $May_ad_by, 
                    'Jun_ad_by' => $Jun_ad_by, 
                    'Jul_ad_by' => $Jul_ad_by, 
                    'Aug_ad_by' => $Aug_ad_by, 
                    'Sep_ad_by' => $Sep_ad_by, 
                    'Oct_ad_by' => $Oct_ad_by, 
                    'Nov_ad_by' => $Nov_ad_by, 
                    'Dec_ad_by' => $Dec_ad_by,

                /*fca Id*/
                    'Jan_id' => $Jan_id,
                    'Feb_id' => $Feb_id,
                    'Mar_id' => $Mar_id,
                    'Apr_id' => $Apr_id, 
                    'May_id' => $May_id, 
                    'Jun_id' => $Jun_id, 
                    'Jul_id' => $Jul_id, 
                    'Aug_id' => $Aug_id, 
                    'Sep_id' => $Sep_id, 
                    'Oct_id' => $Oct_id, 
                    'Nov_id' => $Nov_id, 
                    'Dec_id' => $Dec_id,


                /*Check if there is files here*/
                    'Jan_files' => $this->checkIfTherefile($Jan_id)->count() > 0 ? 1 : 0,
                    'Feb_files' => $this->checkIfTherefile($Feb_id)->count() > 0 ? 1 : 0,
                    'Mar_files' => $this->checkIfTherefile($Mar_id)->count() > 0 ? 1 : 0,
                    'Apr_files' => $this->checkIfTherefile($Apr_id)->count() > 0 ? 1 : 0, 
                    'May_files' => $this->checkIfTherefile($May_id)->count() > 0 ? 1 : 0, 
                    'Jun_files' => $this->checkIfTherefile($Jun_id)->count() > 0 ? 1 : 0, 
                    'Jul_files' => $this->checkIfTherefile($Jul_id)->count() > 0 ? 1 : 0, 
                    'Aug_files' => $this->checkIfTherefile($Aug_id)->count() > 0 ? 1 : 0, 
                    'Sep_files' => $this->checkIfTherefile($Sep_id)->count() > 0 ? 1 : 0, 
                    'Oct_files' => $this->checkIfTherefile($Oct_id)->count() > 0 ? 1 : 0, 
                    'Nov_files' => $this->checkIfTherefile($Nov_id)->count() > 0 ? 1 : 0, 
                    'Dec_files' => $this->checkIfTherefile($Dec_id)->count() > 0 ? 1 : 0,


                /*Expiration date*/
                    'exp_Jan' => $Jan_exp,
                    'exp_Feb' => $Feb_exp,
                    'exp_Mar' => $Mar_exp,
                    'exp_Apr' => $Apr_exp, 
                    'exp_May' => $May_exp, 
                    'exp_Jun' => $Jun_exp, 
                    'exp_Jul' => $Jul_exp, 
                    'exp_Aug' => $Aug_exp, 
                    'exp_Sep' => $Sep_exp, 
                    'exp_Oct' => $Oct_exp, 
                    'exp_Nov' => $Nov_exp, 
                    'exp_Dec' => $Dec_exp,


                /*Percentage*/
                    'Jan_percent' => $Jan_percent,
                    'Feb_percent' => $Feb_percent,
                    'Mar_percent' => $Mar_percent,
                    'Apr_percent' => $Apr_percent, 
                    'May_percent' => $May_percent, 
                    'Jun_percent' => $Jun_percent, 
                    'Jul_percent' => $Jul_percent, 
                    'Aug_percent' => $Aug_percent, 
                    'Sep_percent' => $Sep_percent, 
                    'Oct_percent' => $Oct_percent, 
                    'Nov_percent' => $Nov_percent, 
                    'Dec_percent' => $Dec_percent,

                

                /*checker if exists*/
                    'Jan' => $Jan,
                    'Feb' => $Feb,
                    'Mar' => $Mar,
                    'Apr' => $Apr, 
                    'May' => $May, 
                    'Jun' => $Jun, 
                    'Jul' => $Jul, 
                    'Aug' => $Aug, 
                    'Sep' => $Sep, 
                    'Oct' => $Oct, 
                    'Nov' => $Nov, 
                    'Dec' => $Dec
            ];
        }


        //fcaSettings
        $hfi_item = LimitAccess::select([
                        DB::raw('month as id'),
                        DB::raw('num_of_days as value')
                    ])->where('company_code',1)->get();
        $ofi_item = LimitAccess::select([
                        DB::raw('month as id'),
                        DB::raw('num_of_days as value')
                    ])->where('company_code',2)->get();

        /*get threshold percentage*/
        $fcaExp = FcaExpSettings::where('id',3)->get();


        $response['threshold_percent'] = $fcaExp->count() > 0 ?  $fcaExp[0]->default_expiration_days : 0;
        $response['data'] = $dataset;
        $response['hfi_items'] = $hfi_item;
        $response['ofi_item'] = $ofi_item;
        return response()->json($response);
    }
    
    public function uploadFcaFile(Request $request){
        $response = [];
        

        $uploadfiles = $request->file('file');
        //print_r($request->file('file'));

        foreach ($uploadfiles as $key => $value) {
            //echo $value->getClientOriginalExtension();
            $file_name_before = $value->getClientOriginalName();
            $file_ext = $value->getClientOriginalExtension();
            $file_name_after = $value->hashName();
            $file_size = $value->getClientSize();
            $file = $value->storeAs('public/fca_files',$file_name_after);
            

            $getExp = FcaExpSettings::where('id', 2)->first();
            $exp_date = Carbon::now()->addDays($getExp->default_expiration_days)->format('Y-m-d H:i:s');


            #old
            $store_id = $request->store_id;
            $store_name = $request->store_name;
            $year = $request->year;
            $month = $request->month;
            $month_num = $this->getMonthNum($month);
            $fca_id = $request->fca_id;

            /*Check if there is file or not if not don't allow to upload*/

            if(empty($fca_id)){
                $fca_query = Fca::updateOrCreate(
                        [
                            'store_id' => $store_id,
                            'year_filed'=>$year,
                            'user_id'=> Auth::user()->id,
                            'month_filed_num'=> $month_num,
                            'month_filed_string'=>$month
                        ],
                        [
                            'store'=> $store_name,
                            'expiration_date'=> $exp_date
                        ]
                    );
                if($fca_query){
                    $fca = new FcaFiles;
                    $fca->fca_id = $fca_query->id;
                    $fca->file_name = $file_name_after;
                    $fca->orig_file_name =  $file_name_before;
                    $fca->file_size =  $file_size;
                    $fca->file_path = storage_path('app/public/fca_files/'.$file_name_after.'');
                    $fca->file_ext = $file_ext;
                    $fca->save();
                } 
            }else{
                $fca = new FcaFiles;
                $fca->fca_id = $fca_id;
                $fca->file_name = $file_name_after;
                $fca->orig_file_name =  $file_name_before;
                $fca->file_size =  $file_size;
                $fca->file_path = storage_path('app/public/fca_files/'.$file_name_after.'');
                $fca->file_ext = $file_ext;
                $fca->save();
            }
        }
        return response()->json(['']);
    }

    public function removeFile(Request $request){
        $response = [];
        $file_id = $request->file_id;

        $output = '';
        $find_file = FcaFiles::where('id',$file_id)->get();
        $fname = '';
        if($find_file->count()>0){
            $fname = $find_file[0]->file_name;
        }

        if(is_file(storage_path('\app\public\fca_files\\'.$fname))){
            if(unlink(storage_path('\app\public\fca_files\\'.$fname))){
                /*if successfully removed from folder*/
            }
        }
        $remove = FcaFiles::where('id',$file_id)->delete();
        if($remove){
            $output = 'success';
        }

        $response['data'] = $output;
        return response()->json($response);
    }


    public function getMonthNum($month){
        switch ($month) {
            case 'Jan':
                return 1;
                break;
            case 'Feb':
                return 2;
                break;
            case 'Mar':
                return 3;
                break;
            case 'Apr':
                return 4;
                break;
            case 'May':
                return 5;
                break;
            case 'Jun':
                return 6;
                break;
            case 'Jul':
                return 7;
                break;
            case 'Aug':
                return 8;
                break;
            case 'Sep':
                return 9;
                break;
            case 'Oct':
                return 10;
                break;
            case 'Nov':
                return 11;
                break;
            case 'Dec':
                return 12;
                break;
            default:
                break;
        }
    }
  
    /*Checker if the this year has to be got a data*/
    public function checkIfDataExists($store_id, $store_name, $year_filed, $month_filed){
        $response = '';
        $fca = Fca::where('store_id',$store_id)
                      ->where('store',$store_name)
                     
                      ->where('year_filed',$year_filed)
                      ->where('month_filed_num', $month_filed)
                      ->get();

        return $fca;
    }

    public function getarl_dashboard(Request $request){
        $response = [];

        /*get fucking arls*/
        $arls = User::whereHas('roles',function($q){
                    $q->where('name','arl_am');
                })
                ->get()
                ->toArray();
        $dataset = [];
        foreach ($arls as $key => $value) {
            $dataset[] = [
                'value'=>$value['id'],
                'text'=>$value['name'] . ' ' .$value['last_name']
            ];
        }
        $response['data'] = $dataset;
        return response()->json($response);
    }

    public function saveOtherDetails(Request $request){
        $response = [];
        /*BEFORE SAVING MAKE SURE W*/
        $ratings = $request->ratings;

        $audit_date = $request->audit_date;
        $audit_by = $request->audit_by;
        $remarks = (is_null($request->remarks) ? '' : $request->remarks);

        $fca_id = $request->fca_id;
        $store_id = $request->store_id;
        $store_name = $request->store_name;
        $year = $request->year;
        $month = $request->month;
        $month_num = $this->getMonthNum($month);

        $fcaSettings = FcaExpSettings::where('id',2)->first();
        $exp_date = Carbon::now()->addDays($fcaSettings->default_expiration_days)->format('Y-m-d H:i:s');
        $defaultPercent = FcaExpSettings::where('id',3)->first();
        $gago = $defaultPercent->default_expiration_days;

        //echo $defaultPercent->default_expiration_days;
        $isPassed = 1;
        if($ratings <  $defaultPercent->default_expiration_days){
            $isPassed = 0;
        }


        $name = ucwords(strtolower(Auth::user()->name)). ' '. ucwords(strtolower(Auth::user()->last_name));
        $subject = '[Financial Control Audit] - Report from '.$name;


        /*If not empty we need to send email*/
        if(empty($fca_id)){
            $insert = Fca::updateOrCreate(
                    [
                        'store_id'=>$store_id,
                        'store'=>$store_name,
                        'month_filed_num'=>$month_num,
                        'month_filed_string'=>$month,
                        'year_filed'=>$year,
                    ],
                    [
                        'user_id'=>Auth::user()->id,
                        'percentage'=>$ratings,
                        'remarks'=>$remarks,
                        'expiration_date'=>$exp_date,
                        'audit_by'=>$audit_by,
                        'isPassed'=>$isPassed,
                        'isRead'=>1,
                        'audit_date'=>$audit_date
                    ]
                );
            if($insert){
                /*get the user that will sent by email*/
                $users = FcaSettingsModel::groupBy('user_id')->get();
                foreach ($users as $key => $value) {
                    $user = User::find($value->user_id);
                    $msg_head = "Hi ".ucwords(strtolower($user->name)). ' '. ucwords(strtolower($user->last_name)).',';
                    $msg_body = "Please be inform that ".$name." has successfully submitted a Financial Control Audit Report of store ".$store_name." by the month of ".$month." year ".$year." with the following details below. Plase login to our new FCA website to view the details thank you.";

                    $data_content = [
                        'ratings' => $ratings,
                        'remarks'=> $remarks,
                        'audit_date'=>$audit_date,
                        'store' => $this->getWhatStore($store_id),
                        'month'=>$month,
                        'audit_by'=>$audit_by,
                        'msg_head'=>$msg_head,
                        'msg_body'=>$msg_body
                    ];

                    Mail::send(['html'=>'mail'], $data_content, function($msg) use ($subject, $user, $store_id){
                        $msg->to([$user->email,'biansor.almerol@hiflyer.ca']); //send this fucking mail
                        $msg->cc($this->whoToSend($store_id));
                        $msg->subject($subject);
                    });
                }

                /*send to my self*/
                $msg_head = "Hi ".$name;
                $msg_body = "Your Financial Control Audit report has successfully submited.";

                $data_content = [
                    'ratings' => $ratings,
                    'remarks'=> $remarks,
                    'audit_date'=>$audit_date,
                    'store' => $this->getWhatStore($store_id),
                    'month'=>$month,
                    'audit_by'=>$audit_by,
                    'msg_head'=>$msg_head,
                    'msg_body'=>$msg_body
                ];

                Mail::send(['html'=>'mail'], $data_content, function($msg) use ($subject, $store_id){
                    $msg->to([Auth::user()->email]); //send this fucking mail
                    $msg->cc($this->whoToSend($store_id));
                    $msg->subject($subject);
                });
                if(!Mail::failures()){
                    $fca_email = Fca::find($insert->id);
                    $fca_email->isEmailed = 1;
                }

                $response['data'] = 'success';
                $response['fca_id'] = $insert->id;
            }
        }else{

            //save the previous details
            $fca = Fca::find($fca_id); 

            /*updae*/
            $insertorupdate = FcaOldRecord::updateOrCreate(
                [
                    'fca_id'=>$fca_id,
                    'store_id'=>$fca->store,
                    'percentage'=>$fca->percentage,
                    'audit_by_name'=>Auth::user()->name.' '.Auth::user()->last_name
                ]
            );
            $fca->percentage = $ratings;
            $fca->audit_date = $audit_date;
            $fca->audit_by = $audit_by;
            $fca->remarks = $request->remarks;
            $fca->isPassed = $isPassed;
            $fca->isRead = 1;
            $save = $fca->save();
            if($save){
                $response['data'] = 'success';
                $response['fca_id'] = $fca_id;
            }
        }

        return response()->json($response);
    }

    public function get_files_uploaded(Request $request){
        $response = [];
        $fca_id = $request->fca_id;

        ///echo $fca_id;
        $getFiles = FcaFiles::where('fca_id',$fca_id)->orderBy('created_at')->get();
        $dataset = [];
        foreach ($getFiles as $key => $value) {
            $dataset[] = [
                'id'=>$value->id,
                'original_file_name'=> $value->orig_file_name,
                'file_name'=>$value->file_name,
                'size'=>$value->file_size,
                'ext'=>$value->file_ext
            ];
        }

        $getFca = Fca::where('isPassed', 1)->where('id',$fca_id)->get();


        $response['isHave'] = ($getFiles->count() > 0 || $getFca->count() > 0 ? 1: 0);
        $response['data'] = $dataset;
        return response()->json($response);
    }

    public function checkIfTherefile($fca_id){
        $response= [];
        $fca_files = FcaFiles::where('fca_id',$fca_id)->get();
        return $fca_files;
    }

    public function downloadFile($fname, $rfname){
        $response = [];
        

        if(is_file(storage_path('app/public/fca_files/'.$fname))){
            $response['data'] = 'success';
            $response['f_name'] = $fname;
      
        }else{
            $response['f_name'] = $rfname;
            $response['data'] = 'success';
        }


        return response()->json($response);
    }

    public function exportFca(Request $request){
        $response = [];
        $year = $request->currentYear;
        $stores = $this->get_stores();
        $dataset = [];



        foreach ($stores as $key => $value) {
            /*search in the database data that with this condition*/

            /*print_r($this->getExportingData($year,2,Auth::user()->id, $value['text']));*/
            $jan = count($this->getExportingData($year,1,Auth::user()->id, $value['text'])) > 0 ? $this->getExportingData($year,1,Auth::user()->id, $value['text'])[0]['percentage'] : 0;
            $feb = count($this->getExportingData($year,2,Auth::user()->id, $value['text'])) > 0 ? $this->getExportingData($year,2,Auth::user()->id, $value['text'])[0]['percentage'] : 0;
            $apr = count($this->getExportingData($year,3,Auth::user()->id, $value['text'])) > 0 ? $this->getExportingData($year,3,Auth::user()->id, $value['text'])[0]['percentage'] : 0;
            $mar = count($this->getExportingData($year,4,Auth::user()->id, $value['text'])) > 0 ? $this->getExportingData($year,4,Auth::user()->id, $value['text'])[0]['percentage'] : 0;
            $may = count($this->getExportingData($year,5,Auth::user()->id, $value['text'])) > 0 ? $this->getExportingData($year,5,Auth::user()->id, $value['text'])[0]['percentage'] : 0;
            $jun = count($this->getExportingData($year,6,Auth::user()->id, $value['text'])) > 0 ? $this->getExportingData($year,6,Auth::user()->id, $value['text'])[0]['percentage'] : 0;
            $jul = count($this->getExportingData($year,7,Auth::user()->id, $value['text'])) > 0 ? $this->getExportingData($year,7,Auth::user()->id, $value['text'])[0]['percentage'] : 0;
            $aug = count($this->getExportingData($year,8,Auth::user()->id, $value['text'])) > 0 ? $this->getExportingData($year,8,Auth::user()->id, $value['text'])[0]['percentage'] : 0;
            $sep = count($this->getExportingData($year,9,Auth::user()->id, $value['text'])) > 0 ? $this->getExportingData($year,9,Auth::user()->id, $value['text'])[0]['percentage'] : 0;
            $oct = count($this->getExportingData($year,10,Auth::user()->id, $value['text'])) > 0 ? $this->getExportingData($year,10,Auth::user()->id, $value['text'])[0]['percentage'] : 0;
            $nov = count($this->getExportingData($year,11,Auth::user()->id, $value['text'])) > 0 ? $this->getExportingData($year,11,Auth::user()->id, $value['text'])[0]['percentage'] : 0;
            $dec = count($this->getExportingData($year,12,Auth::user()->id, $value['text'])) > 0 ? $this->getExportingData($year,12,Auth::user()->id, $value['text'])[0]['percentage'] : 0;

            $dataset[] = [
                'Stores' => $value['text'],
                'January' =>$jan,
                'February' =>$feb,
                'March' =>$mar,
                'April' =>$apr,
                'May' =>$may,
                'June' =>$jun,
                'July' =>$jul,
                'August' =>$aug,
                'September' =>$sep,
                'October' =>$oct,
                'November' =>$nov,
                'December' =>$dec            
            ];
        }
        $export = Excel::create('Financial Control Audit '. Carbon::now()->format('F j, Y'), function($excel) use ($dataset){
            $excel->sheet('Sheet 1', function($sheet) use ($dataset) {
                $sheet->fromArray($dataset, 'null','A1', true);
            });
        })->store('xlsx','public/reports',true);


        $response['data'] = $dataset;
        $response['exportedData'] = $export;

        return response()->json($response);
    }

    public function getExportingData($year,$month,$authUser,$store){
        $fca = Fca::where('user_id',$authUser)
                      ->where('year_filed',$year)
                      ->where('store',$store)
                      ->where('month_filed_num', $month)
                      ->get()
                      ->toArray();
        return $fca;
    }

    public function get_stores()
    {
            
        $user_id = User::find(Auth::id());
        $stores = UserToStoreBridge::select([
                DB::raw('id'),
                DB::raw('store_id'),
            DB::raw('store_id_name')
        ])
        ->with('stores')
        ->whereDoesntHave('stores', function($q){
            $q->where('is_active',0);
        });
        if ($user_id->hasRole(['arl_am']) || !$user_id->hasRole(['admin', 'owner'])) {
            $stores->where('user_id',Auth::user()->id);   
        }
        $stores = $stores->groupBy('store_id_name')
        ->orderBy('store_id_name','asc')
        ->get();
        $dataset = [];
        foreach ($stores as $key => $value) {
            $dataset[] = array(
                'value'=> $value->store_id,
                'text'=> $value->store_id_name,
                'company'=>$value->stores->company
            );
        }
        return $dataset;
    }


    /*get other scores*/
    public function getPreviousScore(Request $request){
        $response = [];
        $fca_id = $request->fca_id;
        $oldRecord = FcaOldRecord::where('fca_id',$fca_id)->get();
        $response['data'] = $oldRecord;
        return response()->json($response);
    }

    public function getExempted(Request $requet){
        $response = [];
        $getExemptStore = ExemptStore::all();
        $response['data'] = $getExemptStore;

        return response()->json($response);
    }
    public function whoToSend($store_id){
        $sendto = '';
        //echo $store_name;
        $store = Stores::where('id', $store_id)->get();
        if($store->count()>0){
            if ($store[0]->city_id == 56 || $store[0]->city_id == 34) { //winipeg manitoba
                ///$sendto = 'biansor.almerol@gmail.com';
                $sendto = ['ronda.brophy@hiflyer.ca'];
            } else if ($store[0]->city_id == 46) { //calgery
                //$sendto = 'aldrinbartolome082@gmail.com';
                $sendto = ['cynthia.abanes@hiflyer.ca'];
            } else if ($store[0]->city_id == 51) {
                //$sendto = 'biansor.almerol@gmail.com';
                $sendto = ['norman.murehwa@hiflyer.ca'];
            }
        }
        return $sendto;
    }

    public function getWhatStore($id){
        $current_store = 'N/A';
        $store = Stores::where('id',$id)->get();
        if($store->count() > 0){
            $current_store = $store[0]->store_id;
        }
        return $current_store;
    }

    
}
