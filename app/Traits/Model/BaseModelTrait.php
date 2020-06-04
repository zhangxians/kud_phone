<?php
namespace App\Traits\Model;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

trait BaseModelTrait
{
    //查询的字段
    public function getColumns(){
        $newColumns=[];
        $tableName=$this->table;
        $columns = config('model-service.columns.'.$tableName);
        if(method_exists($this,'exceptColumns'))
        {
            if(isset($columns)){
                foreach ($columns as $item){
                    if(!in_array($item,$this->exceptColumns())){
                        array_push($newColumns,$item);
                    }
                }
            }
        }else {
            return $columns;
        }
        return $newColumns;

//        return Cache::tags('tb_columns')->rememberForever($tableName.'_columns', function ()use($tableName) {
//            $columns = Schema::getColumnListing($tableName);
//            return $columns;
//        });
    }

    /**
     * 重载getFillable
     * @return array
     */
    public function getFillable()
    {
        $newColumns=[];
        $tableName=$this->table;
        $columns = config('model-service.columns.'.$tableName);
        if(isset($columns)){
            foreach ($columns as $item){
                if(!in_array($item,['id','deleted_at','created_at','updated_at'])){
                    array_push($newColumns,$item);
                }
            }
        }
        return $newColumns;


//        return Cache::tags('tb_columns')->rememberForever($tableName.'_fillable', function ()use($tableName) {
//            $columns = Schema::getColumnListing($tableName);
//            $newColumns=[];
//            foreach ($columns as $item){
//                if(!in_array($item,['id','deleted_at','created_at','updated_at'])){
//                    array_push($newColumns,$item);
//                }
//            }
//            return $newColumns;
//        });
    }
}
