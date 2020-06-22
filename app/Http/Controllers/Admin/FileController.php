<?php

namespace App\Http\Controllers\Admin;

use App\Traits\Controller\ImageTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
use Illuminate\Support\Str;

/**
 * 文件相关操作
 */
class FileController extends Controller
{

    use ImageTrait;

    /**
     * 图片-文件上传接口
     * Created by ZX.
     * @param int $type
     * @param Request $request
     * @return string
     */
    public function fileUpload($type=0,Request $request){
        if(in_array($type,['base64'])){
            //base64 图片格式上传
            return $this->base64_image_content($request->file,config('app.fileUpload.'.$type));
        }elseif (in_array($type,['excel'])){
            //文件上传
            return $this->store_file($request->file,config('app.fileUpload.'.$type));
        }
        $file=$request->file('file');
        if(!$file||!($file->isValid())){return json_fail('上传失败');}
        $res= $this->store_img($file,config('app.fileUpload.'.$type));
        return $res;
    }


    /**
     * [将Base64图片转换为本地图片并保存]
     * @param $base64_image_content [要保存的Base64]
     * @param $path [要保存的路径]
     * @return bool|string
     */
    public function base64_image_content($base64_image_content,$path){
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2];        $ymd=date("Y-m-d");
            $basePutUrl = config('app.fileUpload.filePathHead').$path.$ymd;
            if(!in_array($type,['jpeg','jpg','png','gif','JPEG','JPG','PNG','GIF'])){
                return ['msg'=>'请上传规定格式的图片','code'=>1,'path'=>''];
            }
            //创建文件夹
            if(!file_exists($basePutUrl)){
                mkdir($basePutUrl, 0700,true);
            }
            //文件名称
            $fileName=time().'-'.str_random(8).'.'.$type;
            //文件相对路径
            $filepath=$basePutUrl.'/'.$fileName;
            //文件绝对路径
            $local_file_url = $filepath;
            //文件存储
            if (file_put_contents($local_file_url, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                return ['msg'=>'图片存储成功','code'=>0,'path'=>"/storage".$path.$ymd.'/'.$fileName];
            }else{
                return ['msg'=>'图片存储失败','code'=>1,'path'=>''];
            }
        }else{
            return ['msg'=>'错误的图片格式','code'=>1,'path'=>''];
        }
    }



    /**
     * 图片存储
     * Created by ZX.
     * @param $file |文件
     * @param $path |路径
     * @param int $max_width  |最大宽度 压缩
     * @param int $size   |允许大小
     * @param array $type  |图片类型限制
     * @return array
     */
    private function store_img($file,$path,$max_width=1420,$size=20*1024*1024,$type=['jpg','jpeg','png','JPG','JPEG','PNG']){
        $fileSize=$file->getSize();
        if($fileSize>$size){//限制文件上传为 20 m;
            return ['msg'=>'上传文件过大','code'=>1,'path'=>''];
        }
        $ext=$file->getClientOriginalExtension();
        if(!in_array(strtolower($ext),$type)){
            return ['msg'=>'请上传规定格式的图片','code'=>1,'path'=>''];
        }

        $ymd=date("Y-m-d");
        $fileName=time().'-'.str_random(8).'.'.$ext;
        $filepath= config('app.fileUpload.filePathHead').$path.$ymd;
        if(!\file_exists($filepath)){mkdir($filepath, 0777, true);}
        $filepath=$filepath.'/'.$fileName;
        //图片压缩
        $b = $this->compressImg($file,$filepath , $max_width, 85);
        $apath='/storage'.$path.$ymd.'/'.$fileName;
        return $b?json_success('上传成功',$apath):json_fail('上传失败');

    }

    /**
     * 文件上传
     * @param $file
     * @param $path
     * @param float|int $size
     * @param array $type
     * @return array|string
     */
    private function store_file($file,$path,$size=30*1024*1024,$type=['xlsx','xls','docx','doc','pptx','ppt','png','jpg','jpeg','gif','rar','zip','pdf','PDF']){
        $fileSize=$file->getSize();
        if($fileSize>$size){//限制文件上传为 30 m;
            return ['msg'=>'上传文件过大','code'=>1,'path'=>''];
        }
        $ext=$file->getClientOriginalExtension();
        if(!in_array(strtolower($ext),$type)){
            return ['msg'=>'文件格式有误！','code'=>1,'path'=>''];
        }
        $fileName=time().'-'.Str::random(20).'.'.$ext;
        $ymd=date("Y-m-d");
        $filepath= config('app.fileUpload.filePathHead').$path.$ymd;
        if(!\file_exists($filepath)){
            mkdir($filepath, 0777, true);
        }
        $b = move_uploaded_file($file, $filepath.'/'.$fileName);
        $apath='/storage'.$path.$ymd.'/'.$fileName;
        return $b?json_success('上传成功',$apath):json_fail('上传失败');

    }

}
