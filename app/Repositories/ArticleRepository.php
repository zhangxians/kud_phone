<?php namespace App\Repositories;

use App\Exceptions\ValidateException;
use App\Repositories\CommonRepository;
use Illuminate\Support\Arr;

class ArticleRepository extends CommonRepository {

    /**
     * 新增/更新 操作
     * @param $inputs
     * @return mixed
     * @throws ValidateException
     */
    public function upsert($inputs){
        $content=$inputs['content'];
        //xss过滤
        xss_filter($inputs);
        $inputs['content']=$content;
        $id = Arr::get($inputs,'id',null);
        if($id!=null){
            $model=$this->getById($id);
        }else{
            $model = new $this->model;
        }
        $model->fill($inputs);
        $model->save();
        return $model;
    }

    /**
     * 根据条件查询总数量
     * @param $where
     * @return mixed
     */
    public function getCountWhere($where)
    {
        return $this->model->where($where)->count();
    }


    /**
     * 根据条件获得分页数据
     * @param $args
     * @return mixed
     */
    public function getPageListExt($args)
    {
        $pageSize=Arr::get($args,'limit',15);
        $pageNum=Arr::get($args,'page',1);
        $orderby=Arr::get($args,'orderby',['id','desc']);
        $selects=Arr::get($args,'selects','*');
        $res=$this->QueryBuilder($args);
        $res = $this->pagetemp($res,$pageSize, $pageNum,$orderby,$selects);
        return $res;
    }

    /**
     * 根据条件获得总条数
     * @param $args
     * @return mixed
     */
    public function getCountExt($args)
    {
        $res=$this->QueryBuilder($args)->count();
        return $res;
    }

    public function QueryBuilder($args){
        $keyword=Arr::get($args,'keyword',null);
        $id=Arr::get($args,'id',null);
        $title=Arr::get($args,'title',null);
        $user_id=Arr::get($args,'user_id',null);

        $is_deleted=Arr::get($args,'is_deleted',null);

        $res=$this->model;
        if($is_deleted==1){
            $res=$res->onlyTrashed();
        }

        if($id!=null){
            $res=$res->where('id',$id);
        }
        if($title!=null){
            $res=$res->where('title',$title);
        }
        if($user_id!=null){
            $res=$res->where('user_id',$user_id);
        }

        if($keyword!=null){
            $keyword=d_sql_injection($keyword);
            $res=$res->where(function($query) use($keyword){
                $query->where("title","like","%{$keyword}%")
                    ->OrWhere("summary","like","%{$keyword}%");
            });
        }
        return $res;
    }
}
