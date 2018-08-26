<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table ='category';
    protected $primaryKey ='cate_id';
    public $timestamps =false;
    protected $guarded =[];

    public function tree()
    {
        $categorys = $this->orderBy('cate_order','asc')->get();
        //dd($categorys[0]->cate_id);
        return $this->getTree($categorys,'cate_name','cate_id','cate_pid');
    }

     public function getTree($data,$field_name,$field_id,$field_pid)
    {
        //dd($data);
        $arr = array();
        foreach ($data as $k=>$v)
        {
        	//var_dump($v);
        	//dd($data[$k]);
            if($v->$field_pid==0)
            {
            	$data[$k]["_cate_name"] = $data[$k]["cate_name"];
            	//echo $v->cate_name;
            	$arr[] = $data[$k];
            	foreach ($data as $m=>$n)
            	{
            		if($n->$field_pid == $v->cate_id)
            		{
            			$data[$m]["_cate_name"] = '---'.$data[$m]["cate_name"];
            			$arr [] = $data[$m];
            		}
            	}
            }
        }
        //dd($arr);
        return $arr;
        
    }
}