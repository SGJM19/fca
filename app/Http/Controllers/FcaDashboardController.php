<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Fca;
use App\User;
use App\Stores;
use App\UserToStoreBridge;
use Illuminate\Support\Facades\DB;
use App\FcaExpSettings;
use App\FcaSettingsModel;
use App\FcaFiles;
use App\Http\Controllers\FcaController;
use App\FcaComments;
use Mail;
use Carbon\Carbon;
use Excel;


class FcaDashboardController extends Controller
{
    public function __construct(){
    	$this->middleware('auth:api');
    }

    public function getarl_dashboard(Request $request){
    	$response = [];
    	$year = $request->year;
    	$company = $request->company;
    	$arl = $request->arl;

        //echo $company;
        $stores = $this->getAllstores($company, $arl);

            	
    	$dataset = [];
        $fcaFiles = new FcaController;

    	foreach ($stores->toArray() as $key => $value) {
    		$arl = 'N/A';
    		if(count($value['user_to_store_bridge']) > 0){
    			$arl = $value['user_to_store_bridge'][0]['user_name'];
    		}

            $getArlStore = UserToStoreBridge::where('store_id_name', $value['store_id'])
                                            ->whereHas('user', function ($query) {
                                                $query->withRole('arl_am')
                                                      ->where('status',1);
                                            })
                                            ->whereDoesntHave('stores', function ($q) {
                                                $q->where('is_active', 0);
                                            })
                                            ->groupBy('user_id')
                                            ->get();
                                        $arr_arl =  $getArlStore->pluck('user_name')->toArray();

    		$Jan_data = [];
    		$Feb_data = [];
    		$Mar_data = [];
    		$Apr_data = [];
    		$May_data = [];
    		$Jun_data = [];
    		$Jul_data = [];
    		$Aug_data = [];
    		$Sep_data = [];
    		$Oct_data = [];
    		$Nov_data = [];
    		$Dec_data = [];

    		$fca = Fca::where('store',$value['store_id'])
    						  ->where('year_filed', $year)
    						  ->get();

    		foreach ($fca as $k => $v) {
    			if($v->month_filed_num == 1){
    				$Jan_data = $v;
                    if($fcaFiles->checkIfTherefile($v->id)->count()>0){
                        $Jan_data['isThereFile'] = 1;
                    }
    			}
                if($v->month_filed_num == 2){
                    $Feb_data = $v;
                    if($fcaFiles->checkIfTherefile($v->id)->count()>0){
                        $Feb_data['isThereFile'] = 1;
                    }
                }
                if($v->month_filed_num == 3){
                    $Mar_data = $v;
                    if($fcaFiles->checkIfTherefile($v->id)->count()>0){
                        $Mar_data['isThereFile'] = 1;
                    }
                }
                if($v->month_filed_num == 4){
                    $Apr_data = $v;
                    if($fcaFiles->checkIfTherefile($v->id)->count()>0){
                        $Apr_data['isThereFile'] = 1;
                    }
                }
                if($v->month_filed_num == 5){
                    $May_data = $v;
                    if($fcaFiles->checkIfTherefile($v->id)->count()>0){
                        $May_data['isThereFile'] = 1;
                    }
                }
                if($v->month_filed_num == 6){
                    $Jun_data = $v;
                    if($fcaFiles->checkIfTherefile($v->id)->count()>0){
                        $Jun_data['isThereFile'] = 1;
                    }
                }
                if($v->month_filed_num == 7){
                    $Jul_data = $v;
                    if($fcaFiles->checkIfTherefile($v->id)->count()>0){
                        $Jul_data['isThereFile'] = 1;
                    }
                }
                if($v->month_filed_num == 8){
                    $Aug_data = $v;
                    if($fcaFiles->checkIfTherefile($v->id)->count()>0){
                        $Aug_data['isThereFile'] = 1;
                    }
                }
                if($v->month_filed_num == 9){
                    $Sep_data = $v;
                    if($fcaFiles->checkIfTherefile($v->id)->count()>0){
                        $Sep_data['isThereFile'] = 1;
                    }
                }
                if($v->month_filed_num == 10){
                    $Oct_data = $v;
                    if($fcaFiles->checkIfTherefile($v->id)->count()>0){
                        $Oct_data['isThereFile'] = 1;
                    }
                }
                if($v->month_filed_num == 11){
                    $Nov_data = $v;
                    if($fcaFiles->checkIfTherefile($v->id)->count()>0){
                        $Nov_data['isThereFile'] = 1;
                    }
                }
                if($v->month_filed_num == 12){
                    $Dec_data = $v;
                    if($fcaFiles->checkIfTherefile($v->id)->count()>0){
                        $Dec_data['isThereFile'] = 1;
                    }
                }
    		}


    		/*January*/	
    		$dataset[] = array(
					'arl' => $arr_arl,
					'store' => $value['store_id'],
					'Jan' => $Jan_data,
                    'Feb' => $Feb_data,
                    'Mar' => $Mar_data,
                    'Apr' => $Apr_data,
                    'May' => $May_data,
                    'Jun' => $Jun_data,
                    'Jul' => $Jul_data,
                    'Aug' => $Aug_data,
                    'Sep' => $Sep_data,
                    'Oct' => $Oct_data,
                    'Nov' => $Nov_data,
                    'Dec' => $Dec_data
    		);
    	}

        /*get if theres fca comment already*/


    	$fcaExp = FcaExpSettings::where('id',3)->get();
        $response['threshold_percent'] = $fcaExp->count() > 0 ? $fcaExp[0]->default_expiration_days : 0;
    	$response['data'] = $dataset;
    	return response()->json($response);
    }

    public function get_arls(Request $request){
    	/*get fucking arls*/
      $arls = User::whereHas('roles',function($q){
                  $q->where('name','arl_am');
              })
              ->get()
              ->toArray();
        $dataset = [];
        $dataset[0] = [
            'value' => 'all',
            'text' => 'All'
        ];
        $count = 1;
        foreach ($arls as $key => $value) {
            $dataset[$count] = [
              'value'=>$value['id'],
              'text'=>$value['name'] . ' ' .$value['last_name']
            ];
        $count++;
        }
        $response['data'] = $dataset;
        return response()->json($response);
    
    }

    public function getReportDetails(Request $request){
        $response = [];
        $fca_id = $request->fca_id;
        

        $getDetails = Fca::where('id',$fca_id)->get();

        $getFiles = FcaFiles::where('fca_id',$fca_id)->get();

        $response['details'] = $getDetails;
        $response['files'] = $getFiles;
        return response()->json($response);
    }

    public function getAllstores($company = 1, $arl = 'all'){

    	$stores = Stores::select([
						DB::raw('stores.store_id'),	
						DB::raw('id')
					])->with(['UserToStoreBridge'=>function($q) use ($arl){
                        if($arl !== 'all'){
                            $q->where('user_id', $arl)
                              ->orderBy('store_id_name','asc');
                        }
                    }])
                    ->with('exemptstore');
                
                    if($arl === 'all' || empty($arl)){

                    }else{
                        $stores->whereHas('UserToStoreBridge', function($q) use ($arl){
                            $q->where('user_id',$arl)
                                ->where('is_active', 1);
                        });
                    }
					$stores->where('is_active', 1);
                    if($company !== 'all'){
                        $stores->where('company', $company);
                    }
                    $stores = $stores->orderBy('store_id','asc')
					->groupBy('store_id')
					->get();
    	return $stores;
    }

    /*submit pending request if the report not looks good*/
    public function submit_pending(Request $request){
        $response = [];
        $respondMsg = $request->respondMsg;
        $isPending = $request->isPending;
        $fca_id = $request->fca_id;
        $date_to_extend = $request->date_to_extend;

        if($isPending){
            /*update the */
            $fca = Fca::find($fca_id);
            $fca->isPassed = 0;
            $fca->expiration_date = $date_to_extend;
            $fca->isReeval = 1;
            $isSave = $fca->save();
            if($isSave){
                $name = User::find($fca->user_id);
                $subject = '[Financial Control Audit] - Request for re-evaluation.';
                $msg_head = 'Hi '.$name->name.''.$name->last_name.' ,';
                $msg_body = 'Your submitted FCA report on '.date('F j, Y',strtotime($fca->created_at)).' of Store '.$fca->store.' for the month of '.$fca->month_filed_string.' '.$fca->year_filed.' is pending for re-evaluation. You can now modify and re-submit your report.';
                $msg_response = $respondMsg;
                $data_config = [
                    'msg_head'=>$msg_head,
                    'msg_body' => $msg_body,
                    'msg_respond' => $msg_response
                ];
                 Mail::send(['html'=>'mailrespond'], $data_config, function($msg) use ($subject, $name){
                    $msg->to([$name->email,'biansor.almerol@hiflyer.ca']); //send this fucking mail
                    $msg->subject($subject);
                });

                $response['status'] = 'success';
            }
        }

        return response()->json($response); 
    }


    public function send_feedback(Request $request){
        $response = [];
        $fca_id = $request->fca_id;
        $remarks = $request->remarks;

        /*insert*/
        if(!empty($fca_id)){
            $insert = new FcaComments;
            $insert->fca_id = $fca_id;
            $insert->comments = $remarks;
            $insert->user_id = Auth::user()->id;
            $ifinsert = $insert->save();
            /*get fca details*/
            $details = Fca::where('id',$fca_id)->first();
            if($ifinsert){
                $fca = Fca::where('id',$fca_id)->first();
                $user = User::find($fca->user_id);
                $subject = '[Financial Control Audit] - Feedback & Comments for. Store '.$fca->store.', '.$fca->month_filed_string.' '.$fca->year_filed.' audit by '.$fca->audit_by.' ';
                $data_config = [
                    'msg_head'=>$remarks,
                    'remarks_by'=>Auth::user()->name.  ' ' . Auth::user()->last_name
                ];
                 Mail::send(['html'=>'mailfeedback'], $data_config, function($msg) use ($subject,$user) {
                    $msg->to([$user->email,'biansor.almerol@hiflyer.ca']); //send this fucking mail
                    $msg->subject($subject);
                });
                $response['status'] = 'success';
            }
        }else{
            $response['status'] = 'failed';
        }
        return response()->json($response);
    }

    public function getFeedBacks(Request $request){
        $response = []; 
        $dataset = [];

        if(Auth::user()->hasRole(['admin','owner','director','president'])){
            $fca = Fca::whereHas('fcacomments')->with(['fcacomments'=>function($q){
                $q->where('isDeleted',0)->orderBy('id','desc');
            }])->get();
        }else{
            $fca = Fca::where('user_id',Auth::user()->id)->whereHas('fcacomments')->with(['fcacomments'=>function($q){
                $q->where('isDeleted',0)->orderBy('id','desc');
            }])->get();
        }
        foreach ($fca as $key => $value) {
            $dataset[] = [
                'fca_id'=>$value->id,
                'store' => $value->store,
                'user_id' => $value->user_id,
                'name_sender'=> ucwords(strtolower(User::find($value->user_id)->name)) . ' ' .ucwords(strtolower(User::find($value->user_id)->last_name))  ,
                'year_filed' => $value->year_filed,
                'month_filed_num' => $value->month_filed_num,
                'month_filed_string' => $value->month_filed_string,
                'percentage' => $value->percentage,
                'remarks' => $value->remarks,
                'expiration_date' => $value->expiration_date,
                'audit_by' => $value->audit_by,
                'audit_date' => date('F j, Y',strtotime($value->audit_date)),
                'created_at' => date('F j, Y',strtotime($value->created_at)),
                'fca_comments'=> $value->fcacomments[0]->comments,
                'isRead'=>$value->isRead
            ];
        }

        $response['data'] = $dataset;
        return response()->json($response);
    }


    public function readComments(Request $request){
        $response = [];
        $fca_id = $request->fca_id;

        if(Auth::user()->hasRole(['admin','owner','director','president'])){
            $fca_comments = Fca::where('id',$fca_id)->with('fcacomments')->get();
        }else{
            $fca_comments = Fca::where('id',$fca_id)->where('user_id',Auth::user()->id)->with('fcacomments')->get();
        }


       
        $dataset = []; 

        // update when they iew it
        $update = Fca::find($fca_id);
        $update->isRead = 1;
        $isUpdate = $update->save();
        if($isUpdate){
            $response['status'] = true;
        }


        foreach ($fca_comments[0]->fcacomments as $key => $value) {
            $dataset[] = [
                'fca_comment_id'=>$value->id,
                'fca_comment'=>$value->comments,
                'fca_id'=>$value->fca_id,
                'user_id'=>$value->user_id,
                'name'=> User::find($value->user_id)->name . ' ' . User::find($value->user_id)->last_name,
                'created_at'=>date('F j, Y g:i a',strtotime($value->created_at)),
                'remaining_time'=> Carbon::parse($value->created_at)->subMinute(5),
            ];
        }


        $response['data'] = $dataset;
        return response()->json($response);
    }

    public function submitReply(Request $request){
        $response = [];
        $fca_id = $request->fca_id;
        $fca_reply = $request->txt_reply;
        $store = $request->store;
        $remarks = $request->remarks;
        $audit_date = $request->audit_date;

        $insert = new FcaComments;
        $insert->fca_id = $fca_id;
        $insert->comments = $fca_reply;
        $insert->user_id = Auth::user()->id;
        $if_insert = $insert->save();

        $dataContainer = [
            'msg_head'=> $fca_reply,
            'remarks_by'=>Auth::user()->name.  ' ' . Auth::user()->last_name
        ];
        $subject = '[Financial Control Audit] - Reply feedback on store - '.$store.' audit date of '.$audit_date.' ';

        /*send to admins*/
        $users = FcaSettingsModel::with('user')->get()->pluck('user.email')->toArray();
        /*Mail first*/  
        if($if_insert){
            Mail::send(['html'=>'mailfeedback'], $dataContainer, function($msg) use ($subject, $users){
                $msg->to($users); //send this fucking mail
                $msg->subject($subject);
            });
            $response['status'] = true;
    }
        return response()->json($response);
    }

    public function exportFcaDashboard(Request $request){
        $response=  [];
        $company = $request->company;
        $year = $request->year;
        $arl = $request->arl;

        $store = $this->getAllstores($company, $arl);
        $dataset = [];
        //$fca = Fca::leftjoin('db_cua.stores','stores.id','tbl_fca.store_id')->get()->toArray();
        foreach ($store as $key => $value) {
            /*GET ARL NAMEs*/
            $getArlStore = UserToStoreBridge::where('store_id_name', $value['store_id'])
                                            ->whereHas('user', function ($query) {
                                                $query->withRole('arl_am')
                                                      ->where('status',1);
                                            })
                                            ->whereDoesntHave('stores', function ($q) {
                                                $q->where('is_active', 0);
                                            })
                                            ->groupBy('user_id')
                                            ->get();
                                        $arr_arl =  $getArlStore->pluck('user_name')->toArray();

            $jan = count($this->getExportingData($year,1,Auth::user()->id, $value->store_id)) > 0 ? $this->getExportingData($year,1,Auth::user()->id, $value->store_id)[0]['percentage'] : 0;
            $feb = count($this->getExportingData($year,2,Auth::user()->id, $value->store_id)) > 0 ? $this->getExportingData($year,2,Auth::user()->id, $value->store_id)[0]['percentage'] : 0;
            $mar = count($this->getExportingData($year,3,Auth::user()->id, $value->store_id)) > 0 ? $this->getExportingData($year,3,Auth::user()->id, $value->store_id)[0]['percentage'] : 0;
            $apr = count($this->getExportingData($year,4,Auth::user()->id, $value->store_id)) > 0 ? $this->getExportingData($year,4,Auth::user()->id, $value->store_id)[0]['percentage'] : 0;
            $may = count($this->getExportingData($year,5,Auth::user()->id, $value->store_id)) > 0 ? $this->getExportingData($year,5,Auth::user()->id, $value->store_id)[0]['percentage'] : 0;
            $jun = count($this->getExportingData($year,6,Auth::user()->id, $value->store_id)) > 0 ? $this->getExportingData($year,6,Auth::user()->id, $value->store_id)[0]['percentage'] : 0;
            $jul = count($this->getExportingData($year,7,Auth::user()->id, $value->store_id)) > 0 ? $this->getExportingData($year,7,Auth::user()->id, $value->store_id)[0]['percentage'] : 0;
            $aug = count($this->getExportingData($year,8,Auth::user()->id, $value->store_id)) > 0 ? $this->getExportingData($year,8,Auth::user()->id, $value->store_id)[0]['percentage'] : 0;
            $sep = count($this->getExportingData($year,9,Auth::user()->id, $value->store_id)) > 0 ? $this->getExportingData($year,9,Auth::user()->id, $value->store_id)[0]['percentage'] : 0;
            $oct = count($this->getExportingData($year,10,Auth::user()->id, $value->store_id)) > 0 ? $this->getExportingData($year,10,Auth::user()->id, $value->store_id)[0]['percentage'] : 0;
            $nov = count($this->getExportingData($year,11,Auth::user()->id, $value->store_id)) > 0 ? $this->getExportingData($year,11,Auth::user()->id, $value->store_id)[0]['percentage'] : 0;
            $dec = count($this->getExportingData($year,12,Auth::user()->id, $value->store_id)) > 0 ? $this->getExportingData($year,12,Auth::user()->id, $value->store_id)[0]['percentage'] : 0;


            $dataset[] = [
                'ARL'=> implode('', $arr_arl),
                'Stores' => $value->store_id,
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
        $export = Excel::create('Financial Control Audit Admin '. Carbon::now()->format('F j, Y'), function($excel) use ($dataset){
            $excel->sheet('Sheet 1', function($sheet) use ($dataset) {
                $sheet->fromArray($dataset,'null','A1',true);
            });
        })->store('xlsx','public/reports',true);


        $response['exportedData'] = $export;
        return response()->json($response);
    }

    public function getExportingData($year,$month,$authUser,$store){
        $fca = Fca::where('year_filed',$year)
                  ->where('store',$store)
                  ->where('month_filed_num', $month)
                  ->get()
                  ->toArray();
        return $fca;
    }

    public function approveOrNot(Request $request){
        $response = [];
        $request->approvedOrnot;
        if(!empty($request->fca_id)){
            $fca = Fca::find($request->fca_id);
            $subject = '';
            if($request->approvedOrnot == 0){
                $fca->isPassed = $request->approvedOrnot;
                $fca->isReeval = 0;
                $subject = '[Financial Control Audit] Re-opening of report: Store '.$fca->store.' month of '.$fca->month_filed_string.' year '.$fca->year_filed.'.';
            }else if($request->approvedOrnot ==1){ // if tis is approved
                $fca->isPassed = 1;
                $fca->isReeval = 0;
                $subject = '[Financial Control Audit] Approving of report: Store '.$fca->store.' month of '.$fca->month_filed_string.' year '.$fca->year_filed.'. ';
            }else if ($request->approvedOrnot ==2){ // if this is re-evaluatione
                $fca->isReeval = 1;
                $fca->isPassed = 0;

            }
            $save = $fca->save();
            if($save){
                if($request->approveOrNot != 2){
                    $name = User::find($fca->user_id);
                    $msg_head = 'Hi '.$name->name.' '.$name->last_name.' ,';
                    if($request->approvedOrnot ==  0){
                        $msg_body = 'Your submitted FCA report on '.date('F j, Y',strtotime($fca->created_at)).' of Store '.$fca->store.' for the month of '.$fca->month_filed_string.' '.$fca->year_filed.' has now been re-opened';
                    }
                    if($request->approvedOrnot == 1){
                        $msg_body = 'Your submitted FCA report on '.date('F j, Y',strtotime($fca->created_at)).' of Store '.$fca->store.' for the month of '.$fca->month_filed_string.' '.$fca->year_filed.' has now been approved.';
                    }
                    $data_config = [
                        'msg_head'=>$msg_head,
                        'msg_body' => $msg_body
                    ];
                    Mail::send(['html'=>'mailopenreopening'], $data_config, function($msg) use ($subject, $name){
                        $msg->to([$name->email,'biansor.almerol@hiflyer.ca']); //send this fucking mail
                        $msg->subject($subject);
                    });
                    $response['status'] = 'success';
                }
                
                
            }
        }
        

        return response()->json($response);
    }
}
