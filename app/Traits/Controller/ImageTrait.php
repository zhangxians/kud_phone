<?php
namespace App\Traits\Controller;


trait ImageTrait
{

    /**
     * 图片处理
     * @param $file 文件对象
     * @param $max_width 最大宽度
     * @param int $quality 压缩后质量
     * @return string 上传到gridFS 后的文件id
     */
    public function compressImg($file,$savepath,$max_width,$quality=85){
        $img=\Image::make($file);
        $width=$img->width();
        if($width>$max_width){
            // 宽度为900,高度自动调整，不会变形
            $img->resize($max_width, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($savepath, $quality);
        }else{
            $img->save($savepath, $quality);
        }

        return $img;
    }

    public function dealUpload($file,$pathName){
        try{
            $ext=$file->getClientOriginalExtension();
            $size=$file->getSize();
            if(!in_array(strtolower($ext),exts_image())){
                return json_fail("上传失败:文件格式错误{$ext}！{$size}");
            }
            if($size>10*1024*1024){
                return json_fail('上传失败:图片太大，最多只能上传1M文件！');
            }
            $day=strtotime(date('Y-m-d'));
            $path="upload/user_avatar/{$day}/";
            $dirPath=public_path("/$path");
            if(!\File::exists($dirPath)){
                \File::makeDirectory($dirPath, 0777,true);
            }
            $fpath=$path.\Str::random(16).'.'.$ext;
            $filepath=public_path($fpath);

            // 压缩图片
            $this->compressImg($file,$filepath,1080,90);

            $url=config('app.url');
            return json_success('上传成功！',"{$url}/{$fpath}");

        }catch (\Exception $e){
            log_exception($e);
            return json_fail('图片上传失败！');
        }
    }
}