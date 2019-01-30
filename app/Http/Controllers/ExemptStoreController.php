<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stores;
use Illuminate\Support\Facades\DB;
use App\ExemptStore;

class ExemptStoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getTable(Request $request){
        $response = [];
        
        $count_total_row = Stores::select(DB::raw('COUNT(id) as total_count'))
                                ->where('is_active',1)->first();
        $stores = Stores::where('is_active',1)->get();
        $xpt = ExemptStore::all();
        $response['data'] = $stores;
        $response['exempt_stores'] = $xpt; 
        $response['total_items'] = $count_total_row->total_count;
        return response()->json($response);
    }

    public function insert_update(Request $request){
        $response = [];
        $store_id = $request->store_id;
        $m_num = $request->m_num;
        $m_txt = $request->m_txt;
        $id = $request->id;

        //check if this si existinng

        $check =  ExemptStore::where('store_id',$id)
                            ->where('store_name',$store_id)
                            ->where('month_num',$m_num)
                            ->where('month_txt',$m_txt)
                            ->get();
        if($check->count() > 0){
            $delete = ExemptStore::where('store_id',$id)
                        ->where('store_name',$store_id)
                        ->where('month_num',$m_num)
                        ->where('month_txt',$m_txt)
                        ->delete();
            if($delete){
                $response['status'] = 'delete';
            }
        }else{
            $insert = ExemptStore::updateOrCreate(
                [
                    'store_id' => $id,
                    'store_name' => $store_id,
                    'month_num' => $m_num,
                    'month_txt' => $m_txt
                ]
            );
            if ($insert->wasRecentlyCreated) {
                $response['status'] = 'success';
            }
        }

        return response()->json($response);
    }
}
