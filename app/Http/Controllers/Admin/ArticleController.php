<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Model\Category;
use Illuminate\Support\Facades\Input;
use App\Http\Model\Article;
use Illuminate\Support\Facades\Validator;

class ArticleController extends CommonController
{ 
   //get.admin/article   全部文章列表  
    public function  index(){
    	//echo '全部文章列表页面';
    	$data = Article::orderBy('art_id','desc')->paginate(10);
        
    	return view('admin/article/index',compact('data'));
    }

        //get.admin/article/create   添加文章
    public function create()
    {
    	//echo '添加文章分类';
        $data = (new Category)->tree();
        //$data = Category::get();
        //dd($data);
    	return view('admin/article/add',compact('data'));
    	
    }

    //post.admin/article   文章提交
    public function store(){
          $input = Input::except('_token','file_upload');
          $input['art_time'] = time();
          //$input['art_content'] = $input['editorValue'];
          //dd($input);
          $rules = [
              'art_title' =>'required',
              'art_content' =>'required',
            ];
            $message = [
              'art_title.required'=>'文章标题不能为空',
              'art_content.required'=>'文章内容不能为空',
             
          ];

            //dd($input);
   	        $validator = Validator::make($input,$rules,$message);
            //dd($validator);
            if($validator->passes()){
               $re = Article::create($input);
               //dd($re);
               if($re){
	           	 return redirect('admin/article');
	           }else{
	           	return back()->with('errors','文章添加失败');
	           }
            }else{
              return back()->withErrors($validator);
              //dd($validator->errors()->all());
                    
            }
    }

    //get.admin/article/{article}/edit  修改文章信息
    public function edit($art_id)
    {
    	//echo $art_id;
    	$data = (new Category)->tree();
    	$field = Article::find($art_id);
    	//dd($field);
    	 
    	//return view('admin.category.edit',compact('field','data'));
    	return view('admin/article/edit',compact('field','data'));
    }

    //put.admin/article/{article}   提交修改文章信息
    public function update($art_id)
    {
    	$input = Input::except('_method','_token','file_upload');
    	//dd($input);
    	$re = Article::where('art_id',$art_id)->update($input);
    	//dd($re);
    	if($re){
            return redirect('admin/article');
    	}else{
            return back()->with('errors','文章更新失败');
    	}
    }

    //delete.admin/article/{article}   删除单个文章
    public function destroy($art_id)
    {
         $re = Article::where('art_id',$art_id)->delete();
        
        if($re){
            $data = [
                'status' => 0,
                'msg' => '文章删除成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '文章删除失败，请稍后重试！',
            ];
        }
        return $data;
    }





}
