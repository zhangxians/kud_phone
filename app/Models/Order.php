<?php namespace App\Models;

class Order extends BaseModel {

    public $table='tb_order';

    //验证规则
    public $rules = [
    ];
    //未通过验证返回的错误消息
    public $messages = [
    ];


    protected $fillable = ['name','phone1','phone2','phone3','phone4',
        'phone5','address','package','ip','user_id','type','desc','created_at','updated_at','deleted_at'];


    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
