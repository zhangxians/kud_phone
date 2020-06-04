<?php


if ( ! function_exists('mylogger')) {
    function mylogger($type,$msg, $data=''){
        if(strlen($data)>1000){
            $data=substr($data,0,1000);
        }
        $created_at=date('Y-m-d h:s:i');

        \App\Jobs\LogsJob::dispatch(compact('type','msg','data','created_at'));
    }
}

//  log_info(__CLASS__,__FUNCTION__,"[授权并登录]client_id:{$client_id}");

if ( ! function_exists('log_debug')) {
    function log_debug($msg, $data=''){
        $type=1;
        mylogger($type,$msg, $data);
    }
}
if ( ! function_exists('log_info')) {
    function log_info($msg, $data=''){
        $type=2;
        mylogger($type,$msg, $data);
    }
}
if ( ! function_exists('log_warn')) {
    function log_warn($msg, $data=''){
        $type=3;
        mylogger($type,$msg, $data);
    }
}
if ( ! function_exists('log_error')) {
    function log_error($msg, $data=''){
        $type=4;
        mylogger($type,$msg, $data);
    }
}
if ( ! function_exists('log_exception')) {
    function log_exception(\Exception $e, $data=''){
        $type=4;
        $file='';
        $line='';
        $url='';
        try{
            $file=$e->getFile();
            $line=$e->getLine();
            $url=request()->fullUrl();
        }catch (\Exception $e){}
        \Illuminate\Support\Facades\Log::error("【url={$url}】   【{$file}={$line}】 【{$e->getMessage()}】 【data={$data}】/r/n{$e->getTraceAsString()}");
        mylogger($type,$e->getMessage(), $data);
    }
}
