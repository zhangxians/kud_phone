<?php

namespace App\Http\Controllers\HomeIndex;

use App\Exceptions\DataNotException;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{


    // 主页
    public function index(Request $request){
        $ip = $request->ip();
        $ip = $this->getIp();

        $ti = time();
        // 获取当前用户正在操作的用户
        $cUser =  Customer::where([['ip',$ip],['is_call',1]])->first();
        // 如果有
        if($cUser){
            $user = $cUser->toArray();
        }else{
            // 一个新的用户
            $user = Customer::where([['type',0],['is_call',0]])->orderBy('updated_at','asc')->first();
            // 设置当前ip用户未在操作电话
            Customer::where('ip',$ip)->update(['is_call'=>0]);

            if($user){
                // 将查询到的电话设置为操作中
                $user->is_call = 1;
                $user->ip = $ip;
                $user->save();
                $user = $user->toArray();
            }else{
                throw new DataNotException('电话打完了！！');
            }
        }
        return view('index',compact('user','ip','ti'));
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
        if(($request->t1??$t2)+10 > $t2){
            return json_fail("间隔时间太短，请在".($request->t1+10-$t2)."秒后再次点击。");
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
