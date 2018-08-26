<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Model\Navs;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class NavsController extends Controller
{
    //get.admin/navs   全部自主导航列表 
    public function index()
    {
    	//echo '全部自主导航列表';
    	$data = navs::orderBy('nav_order','asc')->get();
    	return view('admin/navs/index',compact('data'));
    }
    //修改排序
    //post.admin/navs/changeorder
    public function changeOrder()
    {
    	//echo 234234;\
    	$input = Input::all();
    	//dd($input);
    	$nav = navs::find($input['nav_id']);
    	$nav->nav_order = $input['nav_order'];
    	$re = $nav->update();
    	if($re){
    		$data = [
    			'status' => 0,
    			'msg' => '链接排序更新成功',
    		];
    	}else{
    		$data = [
                'status' => 1,
                'msg' => '链接排序更新失败，请稍后重试',
    		];
    	}
    	return $data;	
    }

    //get.admin/navs/{navs}  显示个人分类信息
    public function show()
    {
    	
    }

    //get.admin/navs/create   添加自主导航
    public function create()
    {
    	//echo '添加自主导航';
        return view('admin/navs/add');
    }

    //post.admin/navs   添加自主导航提交
    public function store()
    {
    	$input = Input::except('_token');
    	//dd($input);
    	$rules = [
              'nav_name' =>'required',
              'nav_url' =>'required',
            ];
            $message = [
              'nav_name.required'=>'链接名称不能为空',
              'nav_url.required'=>'链接url不能为空',
             
          ];

            //dd($input);
   	        $validator = Validator::make($input,$rules,$message);
            //dd($validator);
            if($validator->passes()){
               $re = navs::create($input);
               //dd($re);
               if($re){
               	 return redirect('admin/navs');
               }else{
               	return back()->with('errors','添加自主导航失败');
               }
            }else{
              return back()->withErrors($validator);
              //dd($validator->errors()->all());
                    
            }
    }
      
     //get.admin/navs/{navs}/edit  编辑自主导航
    public function edit($nav_id)
    {
    	//echo $cate_id;
    	$field = navs::find($nav_id);
    	//dd($field);
    	
    	return view('admin.navs.edit',compact('field'));
    }

     //put.admin/navs/{navs}   提交更新自主导航
    public function update($nav_id)
    {
    	//dd(Input::all());
    	$input = Input::except('_token','_method');
    	//dd($input);
    	$re = navs::where('nav_id',$nav_id)->update($input);
    	//dd($re);
    	if($re){
            return redirect('admin/navs');
    	}else{
            return back()->with('errors','自主导航更新失败');
    	}

    }

    //delete.admin/navs/{navs}   删除单个链接
    public function destroy($nav_id)
    {
        $re = navs::where('nav_id',$nav_id)->delete();
        
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


}
