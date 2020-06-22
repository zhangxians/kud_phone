<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CustomerExport;
use App\Imports\CustomerImport;
use App\Models\Customer;
use App\Models\TbIp;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;


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


    public function customerInsert(Request $request){
        $filePath = $request->url??'';
        $filePath = str_replace('storage','storage/app/public',$filePath);
        $data=  \Excel::toCollection(new CustomerImport(), base_path($filePath))->toArray();
        unset($data[0][0]);
        $c_data =[];
        foreach ($data[0] as  $d){
            $c_d = [];
            $c_d['name'] = $d[2];
            $c_d['address'] = $d[4];
            $c_d['package'] = $d[5];
            $c_d['desc'] = $d[6]??'';

            $phone = str_replace('手机:','',$d[7]??'');
            $phones = explode(',',$phone);
            $i = 1;
            $c_d['phone1'] = '';
            $c_d['phone2'] = '';
            $c_d['phone3'] = '';
            $c_d['phone4'] = '';
            $c_d['phone5'] = '';
            foreach ($phones as $p){
                if(trim($p)){
                    $c_d["phone{$i}"] = trim($p);
                    $i++;
                    if($i>5){
                        break;
                    }
                }
            }
            $c_d['type'] = 0;
            $c_d['created_at'] = date('Y-m-d h:i:s');
            $c_d['updated_at'] = date('Y-m-d h:i:s');
            if($c_d['name']&&$c_d['address']&&$c_d['package']){
                $c_data[] = $c_d;
            }
        }

        $res = DB::table('tb_customer')->insert($c_data);
        return $res?json_success('数据上传成功'):json_fail('数据上传失败');

    }
    /**
     * tbip
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tbIp(){
        $ips = TbIp::paginate(20);
        return view('admin.ip',compact('ips'));
    }


    /**
     * 修改ip限制
     * @param Request $request
     * @return string
     */
    public function ipUpdate(Request $request){
        $ip = $request->ip??'';
        $id = $request->id??0;
        $status = $request->status??0;

        $tbip = TbIp::find($id);
        if($tbip){
            $tbip->status = $status;
            $tbip->ip = $ip;
            $res = $tbip->save();
            return $res?json_success('设置成功'):json_fail('设置失败');
        }else{
            return json_fail('该ip不存在');
        }
    }



}
