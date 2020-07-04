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

class IndexController extends Controller
{


    // 主页
    public function index(Request $request){
        $ip = $this->getIp();

        $AuthUser = Auth::user();
        $ti = time();
        // 获取当前用户正在操作的用户
        $cUser =  Customer::where([['ip',$ip],['is_call',1],['user_id',$AuthUser->id]])->first();
        // 如果有
        if($cUser){
            $user = $cUser->toArray();
        }else{
            // 一个新的用户
            $user = Customer::where([['type',0],['is_call',0]])->orderBy('address','asc')->first();
            // 设置当前ip用户未在操作电话
           // Customer::where('ip',$ip)->update(['is_call'=>0]);

            if($user){
                // 将查询到的电话设置为操作中
                $user->is_call = 1;
                $user->ip = $ip;
                $user->user_id = $AuthUser->id;
                $user->save();
                $user = $user->toArray();
            }else{
                throw new DataNotException('电话打完了！！');
            }
        }
        $user['address'] = mb_substr($user['address'],0,-15);
        return view('index',compact('user','ip','ti'));
    }


    /**
     * 需要回拨号的数据
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function needCall(Request $request){
        $ip = $this->getIp();

        $user_id = Auth::user()->id;
        $customer = Customer::where('type',$request->type??4)->with('user');
        $customer=$customer->where('user_id',$user_id);
        $users = $customer->orderBy('updated_at','desc')->paginate(10);
        return view('other',compact('users','ip'));
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
        if($request->t1!=1){
            if(($request->t1??$t2)+5 > $t2){
                return json_fail("间隔时间太短，请在".($request->t1+5-$t2)."秒后再次点击。");
            }
        }

        DB::beginTransaction();
        $res = Customer::where('id',$id)->lockForUpdate()
            ->update(['type'=>$type,'desc'=>$desc,'ip'=> $ip,'is_call'=>0,'user_id'=>$user->id]);
        DB::commit();

        if($type == 5){
            $this->addOrder($id);
        }
        if($res){
            return json_success('操作成功');
        }else{
            return json_fail('操作失败');
        }

    }

    protected function addOrder($customer_id){
        $customer = Customer::find($customer_id);
        if(!$customer){
            return false;
        }
        unset($customer['id']);
        $order = new Order();
        $order->name = $customer->name;
        $order->phone1 = $customer->phone1;
        $order->phone2 = $customer->phone2;
        $order->phone3 = $customer->phone3;
        $order->phone4 = $customer->phone4;
        $order->phone5 = $customer->phone5;
        $order->address = $customer->address;
        $order->package = $customer->package;
        $order->ip = $customer->ip;
        $order->user_id = 0;
        $order->desc = $customer->desc;
        $order->type = 0;
        $order->save();
    }
}
