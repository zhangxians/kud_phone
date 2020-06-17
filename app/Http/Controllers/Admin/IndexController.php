<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;


class IndexController extends Controller
{


    /**
     * 拨号图标页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customer(){
        return view('admin.customer.customer');
    }


    /**
     * 拨号详情
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customerList(Request $request){
        $user_id = $request->user_id??false;
        $type    = $request->type??false;
        $isAll   = $request->isAll??false;
        $users = Customer::where('type',$type)->with('user');
        if($user_id){
            $users=$users->where('user_id',$user_id);
            if($isAll){
                $users=$users->where('updated_at','>',date('Y-m-d 00:00:00'));
            }
        }
        $users = $users->paginate(10);
        return view('admin.customer.list',compact('users','user_id','type','isAll'));
    }


    /**
     * 查询数据统计
     * @param Request $request
     * @return string
     */
    public function customerPageList(){
        $type0 = Customer::where('type',0)->count();
        $type1 = Customer::where('type',1)->count();
        $type2 = Customer::where('type',2)->count();
        $type3 = Customer::where('type',3)->count();
        $type4 = Customer::where('type',4)->count();
        $type5 = Customer::where('type',5)->count();
        $data =[
            ['value'=>$type0,'type'=>0,'name'=>'未处理'],
            ['value'=>$type1,'type'=>1,'name'=>'空号'],
            ['value'=>$type2,'type'=>2,'name'=>'未接听'],
            ['value'=>$type3,'type'=>3,'name'=>'无意愿'],
            ['value'=>$type4,'type'=>4,'name'=>'有意愿，再联系'],
            ['value'=>$type5,'type'=>5,'name'=>'有意愿，待处理'],
        ];
        return json_success('查询成功',$data);
    }


    public function tbIp(){
        return view('admin.customer.customer');
    }

    /**
     * 查询数据统计
     * @param Request $request
     * @return string
     */
    public function ipPageList(){
        $type0 = Customer::where('type',0)->count();
        $type1 = Customer::where('type',1)->count();
        $type2 = Customer::where('type',2)->count();
        $type3 = Customer::where('type',3)->count();
        $type4 = Customer::where('type',4)->count();
        $type5 = Customer::where('type',5)->count();
        $data =[
            ['value'=>$type0,'name'=>'未处理'],
            ['value'=>$type1,'name'=>'空号'],
            ['value'=>$type2,'name'=>'未接听'],
            ['value'=>$type3,'name'=>'无意愿'],
            ['value'=>$type4,'name'=>'有意愿，再联系'],
            ['value'=>$type5,'name'=>'有意愿，待处理'],
        ];
        return json_success('查询成功',$data);
    }



}
