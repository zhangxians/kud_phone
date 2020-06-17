<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model {

    public $table='login_log';

    //验证规则
    public $rules = [
    ];
    //未通过验证返回的错误消息
    public $messages = [
    ];


}
