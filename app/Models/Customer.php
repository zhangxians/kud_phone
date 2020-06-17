<?php namespace App\Models;

class Customer extends BaseModel {

    public $table='tb_customer';

    //验证规则
    public $rules = [
    ];
    //未通过验证返回的错误消息
    public $messages = [
    ];



    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
