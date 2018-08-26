<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Model\Category;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CategoryController extends CommonController
{
    //get.admin/category   全部分类列表 
    public function index()
    {
        //echo 234234;
        //$categorys = Category::all();
        //$data = $this->getTree($categorys,'cate_id','cate_pid');
        //var_dump($categorys);
        //dd($categorys);
        $categorys = (new Category)->tree();
        return view('admin.category.index')->with('data',$categorys);
    }

    // public function getTree($data,$field_id,$field_pid)
    // {
    //     //dd($data);
    //     $arr = array();
    //     foreach ($data as $k=>$v)
    //     {
    //     	//var_dump($v);
    //     	//dd($v);
    //         if($v->$field_pid==0)
    //         {
    //         	$data[$k]["_cate_name"] = $data[$k]["cate_name"];
    //         	//echo $v->cate_name;
    //         	$arr[] = $data[$k];
    //         	foreach ($data as $m=>$n)
    //         	{
    //         		if($n->$field_pid == $v->cate_id)
    //         		{
    //         			$data[$m]["_cate_name"] = '---'.$data[$m]["cate_name"];
    //         			$arr [] = $data[$m];
    //         		}
    //         	}
    //         }
    //     }
    //     return $arr;
    // }
    public function changeOrder()
    {
    	//echo 234234;\
    	$input = Input::all();
    	//dd($input);
    	$cate = Category::find($input['cate_id']);
    	$cate->cate_order = $input['cate_order'];
    	$re = $cate->update();
    	if($re){
    		$data = [
    			'status' => 0,
    			'msg' => '分类排序更新成功',
    		];
    	}else{
    		$data = [
                'status' => 1,
                'msg' => '分类排序更新失败，请稍后重试',
    		];
    	}
    	return $data;	
    }

    //get.admin/category/create   添加分类
    public function create()
    {
    	//echo '添加分裂';
    	//$data = Category::all();
    	$data = Category::where('cate_pid',0)->get();
    	//dd($data);
    	return view('admin/category/add',compact('data'));
    	
    }

    //post.admin/category   添加分类提交
    public function store()
    {
    	$input = Input::except('_token');
    	//dd($input);
    	$rules = [
              'cate_name' =>'required|',
            ];
            $message = [
              'cate_name.required'=>'分类名称不能为空',
             
          ];

            //dd($input);
   	        $validator = Validator::make($input,$rules,$message);
            //dd($validator);
            if($validator->passes()){
               $re = Category::create($input);
               //dd($re);
               if($re){
               	 return redirect('admin/category');
               }else{
               	return back()->with('errors','添加分类失败');
               }
            }else{
              return back()->withErrors($validator);
              //dd($validator->errors()->all());
                    
            }
         
    } 

    //get.admin/category/{category}  显示个人分类信息
    public function show()
    {
    	
    }

    //delete.admin/category/{category}   删除单个分类
    public function destroy($cate_id)
    {
        $re = Category::where('cate_id',$cate_id)->delete();
        Category::where('cate_pid',$cate_id)->update(['cate_pid'=>0]);
        if($re){
            $data = [
                'status' => 0,
                'msg' => '分类删除成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '分类删除失败，请稍后重试！',
            ];
        }
        return $data;
    }

        //get.admin/category/{category}/edit  编辑分类
    public function edit($cate_id)
    {
    	//echo $cate_id;
    	$field = Category::find($cate_id);
    	//dd($field);
    	$data = Category::where('cate_pid',0)->get();
    	return view('admin.category.edit',compact('field','data'));
    }

    //put.admin/category/{category}   提交更新分类
    public function update($cate_id)
    {
    	//dd(Input::all());
    	$input = Input::except('_token','_method');
    	$re = Category::where('cate_id',$cate_id)->update($input);
    	//dd($re);
    	if($re){
            return redirect('admin/category');
    	}else{
            return back()->with('errors','分类信息更新失败');
    	}

    	
    }


}
