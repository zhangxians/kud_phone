<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\DataNotException;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{


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
        $data = User::orderBy('id','desc')->withCount(['customer'=>function($query){
            $query->where([['updated_at','>',date('Y-m-d 00:00:00')],['type','!=',0]]);
        }])->get();
        $socketUser = Cache::get('socketUser')??[];
        foreach ($data as  &$d){
            $d->online = 0;
            foreach ($socketUser as $s){
                if($d->id == $s['user_id']){
                    $d->online = 1;
                    continue;
                }
            }
        }
        unset($d);
        return json_success('查询成功',$data);
    }

    /**
     * 发送message
     * @param Request $request
     * @return string
     */
    public function setMessage(Request $request){
        $user_id = $request->id??-1;
        $message = $request->message??'';
        $socketUser = Cache::get('socketUser')??[];
        if(isset($socketUser[$user_id])){
            $user = $socketUser[$user_id];
            $fd = $user['socket_id'];
            $message = json_encode(['type'=>1,'status'=>0,'msg'=>$message,'id'=>$user_id]);
            $swoole = app('swoole');
            $success = $swoole->push($fd, $message);
            return $success?json_success('已经发送'):json_fail('发送失败！');
        }else{
            return json_fail('该员工已离线！');
        }

    }


    public function userEdit(Request $request){
        $user_id = $request->id??0;
        $name = $request->name??false;
        $password = $request->password??false;

        try {
            $user = User::find($user_id);
            if(!$user){
                return json_fail('该用户不存在');
            }

            $sameNameUser = User::where('username',$name)->first();
            if($sameNameUser&&$sameNameUser->id!=$user_id){
                return json_fail('该用户名已经被使用');
            }

            if($name){
                $user->username = $name;
            }

            if($password){
                $user->password = bcrypt($password);
            }

            $res = $user->save();
            return $res ?json_success('修改成功'):json_fail('修改失败');
        } catch (\Exception $exception) {
            return json_fail();
        }

    }


}
