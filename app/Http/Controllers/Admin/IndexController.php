<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\DataNotException;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{

    public function customer(Request $request){
        return view('admin.customer');
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
            ['value'=>$type0,'name'=>'未处理'],
            ['value'=>$type1,'name'=>'空号'],
            ['value'=>$type2,'name'=>'未接听'],
            ['value'=>$type3,'name'=>'无意愿'],
            ['value'=>$type4,'name'=>'有意愿，再联系'],
            ['value'=>$type5,'name'=>'有意愿，待处理'],
        ];
        return json_success('查询成功',$data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user(){
        return view('admin.user');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userUpdate(Request $request){
        $id = $request->id;
        $status = $request->status;
        $res = User::where('id',$id)->update(['status'=>$status]);
        return $res?json_success('修改成功！'):json_fail('修改失败！');
    }


    /**
     * 查询数据统计
     * @param Request $request
     * @return string
     */
    public function userPageList(){
        $data = User::orderBy('id','desc')->get();
        return json_success('查询成功',$data);
    }
    public function tbIp(Request $request){
        return view('admin.ip');
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
