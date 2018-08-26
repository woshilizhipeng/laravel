<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Model\Links;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LinksController extends Controller
{
    //get.admin/links   全部友情链接列表 
    public function index()
    {
    	//echo '全部友情链接列表';
    	$data = links::orderBy('link_order','asc')->get();
    	return view('admin/links/index',compact('data'));
    }
    //修改排序
    //post.admin/links/changeorder
    public function changeOrder()
    {
    	//echo 234234;\
    	$input = Input::all();
    	//dd($input);
    	$link = Links::find($input['link_id']);
    	$link->link_order = $input['link_order'];
    	$re = $link->update();
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

    //get.admin/links/{links}  显示个人分类信息
    public function show()
    {
    	
    }

    //get.admin/links/create   添加友情链接
    public function create()
    {
    	//echo '添加友情链接';
        return view('admin/links/add');
    }

    //post.admin/links   添加友情链接提交
    public function store()
    {
    	$input = Input::except('_token');
    	//dd($input);
    	$rules = [
              'link_name' =>'required',
              'link_url' =>'required',
            ];
            $message = [
              'link_name.required'=>'链接名称不能为空',
              'link_url.required'=>'链接url不能为空',
             
          ];

            //dd($input);
   	        $validator = Validator::make($input,$rules,$message);
            //dd($validator);
            if($validator->passes()){
               $re = Links::create($input);
               //dd($re);
               if($re){
               	 return redirect('admin/links');
               }else{
               	return back()->with('errors','添加友情链接失败');
               }
            }else{
              return back()->withErrors($validator);
              //dd($validator->errors()->all());
                    
            }
    }
      
     //get.admin/links/{links}/edit  编辑友情链接
    public function edit($link_id)
    {
    	//echo $cate_id;
    	$field = Links::find($link_id);
    	//dd($field);
    	
    	return view('admin.links.edit',compact('field'));
    }

     //put.admin/links/{links}   提交更新友情链接
    public function update($link_id)
    {
    	//dd(Input::all());
    	$input = Input::except('_token','_method');
    	//dd($input);
    	$re = Links::where('link_id',$link_id)->update($input);
    	//dd($re);
    	if($re){
            return redirect('admin/links');
    	}else{
            return back()->with('errors','友情链接更新失败');
    	}

    }

    //delete.admin/links/{links}   删除单个链接
    public function destroy($link_id)
    {
        $re = Links::where('link_id',$link_id)->delete();
        
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
