<?php

namespace App\Http\Controllers\HomeIndex;

use App\Exceptions\DataNotException;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesController extends Controller
{



    public function index(){
        $users = Order::where('type',0)->where('user_id',0)->with('user');
        $users = $users->orderBy('updated_at','desc')->paginate(10);
        return view('sales.customer.list',compact('users'));
    }


    /**
     * 更新数据
     * @param Request $request
     * @return string
     */
    public function receiving(Request $request){
        $id   = $request->id??0;
        $ip = $this->getIp();
        $user = Auth::user();

        DB::beginTransaction();
        $res = Order::where('id',$id)->lockForUpdate()
            ->update(['ip'=> $ip,'user_id'=>$user->id]);
        DB::commit();
        if($res){
            return json_success('操作成功');
        }else{
            return json_fail('操作失败');
        }
    }


    /**
     * 待处理订单详情
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function order(){
        $user = Auth::user();
        $users = Order::where('type',0)->where('user_id',$user->id);
        $users = $users->orderBy('updated_at','desc')->paginate(10);
        return view('sales.customer.order',compact('users'));
    }


    /**
     * 用户设置订单
     * @param Request $request
     * @return string
     */
    public function update(Request $request){
        $id = $request->id??0;
        $ip = $this->getIp();
        $user = Auth::user();
        $desc = $request->desc??'';
        $type = $request->type??0;

        DB::beginTransaction();
        $res = Order::where('id',$id)->lockForUpdate()
            ->update(['ip'=> $ip,'user_id'=>$user->id,'desc'=>$desc,'type'=>$type]);
        DB::commit();
        if($res){
            return json_success('操作成功');
        }else{
            return json_fail('操作失败');
        }
    }


    public function orderList(){
        $user = Auth::user();
        $users = Order::where('type','!=',0)->where('user_id',$user->id);
        $users = $users->orderBy('updated_at','desc')->paginate(10);

        $type0 = Order::where([['type',0],['user_id',$user->id]])->count();
        $type1 = Order::where([['type',1],['user_id',$user->id]])->count();
        $type2 = Order::where([['type',2],['user_id',$user->id]])->count();
        $type3 = Order::where([['type',3],['user_id',$user->id]])->count();
        $data =[
//            ['value'=>$type0,'type'=>0,'name'=>'未处理 '.$type0],
            ['value'=>$type1,'type'=>1,'name'=>'成功办理 '.$type1],
            ['value'=>$type2,'type'=>2,'name'=>'拒绝办理 '.$type2],
            ['value'=>$type3,'type'=>3,'name'=>'其他情况 '.$type3],
        ];

        foreach ($data as $i=>$d){
            if($d['value']==0){
                unset($data[$i]);
            }
        }
        $data = json_encode(array_values($data));
        return view('sales.customer.orderlist',compact('users','data'));
    }
}
