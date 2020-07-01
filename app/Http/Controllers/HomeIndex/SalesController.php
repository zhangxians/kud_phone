<?php

namespace App\Http\Controllers\HomeIndex;

use App\Exceptions\DataNotException;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesController extends Controller
{



    public function index(Request $request){
        $user_id = $request->user_id??false;
        $type    = $request->type??false;
        $isAll   = $request->isAll??false;
        $users = Customer::where('type',$type)->with('user');
        if($user_id){
            $users=$users->where('user_id',$user_id);
            if($isAll){
                $users=Customer::where('updated_at','>',date('Y-m-d 00:00:00'))->where('user_id',$user_id);
            }
        }
        $users = $users->paginate(10);
        return view('sales.customer.list',compact('users','user_id','type','isAll'));
    }


    // 更新数据
    public function updateIndex(Request $request){
        $type = $request->type??false;
        $desc = $request->desc??false;
        $id   = $request->id??0;
        $ip = $this->getIp();
        $user = Auth::user();

        if(!in_array($type,[0,1,2,3,4,5])){
            return json_fail('请求类型错误');
        }
        $t2 = time();
        if(($request->t1??$t2)+5 > $t2){
            return json_fail("间隔时间太短，请在".($request->t1+5-$t2)."秒后再次点击。");
        }
        DB::beginTransaction();
        $res = Customer::where('id',$id)->lockForUpdate()
            ->update(['type'=>$type,'desc'=>$desc,'ip'=> $ip,'is_call'=>0,'user_id'=>$user->id]);
        DB::commit();
        if($res){
            return json_success('操作成功');
        }else{
            return json_fail('操作失败');
        }

    }
}
