<?php

namespace App\Http\Controllers\HomeIndex;

use App\Http\Controllers\Controller;
use App\Models\TbIp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{


    /**
     * 登录页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(Request $request){
        $ip = $this->getIp();
        if(Auth::check()){
            return redirect('');
        }
        return view('login',compact('ip'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function loginIn(Request $request){
        $ip = $this->getIp();
        $username = $request->username??'';
        $password = $request->password??'';
        $canLogin = TbIp::where([['ip',$ip],['status',0]])->count();
        if($canLogin<=0&&$username!='sadmin'){
            return json_fail('当前 IP 不允许登录');
        }

        $res = Auth::guard('web')->attempt(['username'=>$username,'password'=>$password,'status'=>0]);
        $token=$this->makeToken();
        Auth::logoutOtherDevices($password);
        if($res){
            User::where('id',Auth::user()->id)->update(['ip'=>$ip,'token'=>$token]);
            if($username=='sadmin'){
                return json_success('登录成功',['token'=>$token,'url'=>'/customer']);
            }else{
                return json_success('登录成功',['token'=>$token,'url'=>'/']);
            }
        }
        return $res?json_success('登录成功',['token'=>$token,'url'=>'/']):json_fail('登录失败');
    }

    /**
     * 登出
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(){
        User::where('id',Auth::user()->id)->update(['token'=>'']);
        Auth::logout();
        return redirect('/login');
    }
}
