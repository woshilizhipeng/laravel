<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Model\Navs;
use App\Http\Model\Links;
use App\Http\Model\Article;
use App\Http\Model\Category;
class IndexController extends CommonController
{
    public function index()
    {
    	//点击量最最高的六篇文章
    	$hot = Article::orderBy('art_view','desc')->take(6)->get();
    	//dd($hot);
    	$hotRight = Article::orderBy('art_view','desc')->take(5)->get();
    	//dd($hotRight);

    	//图文列表五篇（分页）
        $data = Article::orderBy('art_time','desc')->paginate(5);
        //dd($data);
    	//最新文章八篇
        $new = Article::orderBy('art_time','desc')->take(8)->get();
    	//友情链接
        $links = Links::orderBy('link_order','asc')->get();
        //dd($links);
    
    	//echo '前台首页';
        //$navs = Navs::orderBy('nav_order','asc')->get();
        //dd($navs);
    	return view('home/index',compact('hot','hotRight','data','new','links'));
    }

    public function cate($cate_id)
    {
    	//图文列表四篇（分页）
        $data = Article::where('cate_id',$cate_id)->orderBy('art_time','desc')->paginate(4);
        //dd($data);
    	//echo $cate_id;
    	//查看次数自增
        Category::where('cate_id',$cate_id)->increment('cate_view');

    	$submenu = Category::where('cate_pid',$cate_id)->get();
    	$field = Category::find($cate_id);
        //dd($field);
    	//echo '列表页';
    	//$navs = Navs::orderBy('nav_order','asc')->get();
    	return view('home/list',compact('data','field','submenu'));
    }

    public function article($art_id)
    {
    	$field = Article::join('category','article.cate_id','=','category.cate_id')->where('art_id',$art_id)->first();
        //dd($field);
    	//查看次数自增
        Article::where('art_id',$art_id)->increment('art_view');

    	$article['pre'] = Article::where('art_id','<',$art_id)->orderBy('art_id','desc')->first();
    	$article['next'] = Article::where('art_id','>',$art_id)->orderBy('art_id','asc')->first();
    	
    	//echo '新闻页面';
    	//$navs = Navs::orderBy('nav_order','asc')->get();
    	$data = Article::where('cate_id',$field->cate_id)->orderBy('art_id','desc')->take(6)->get();
    	return view('home/new',compact('field','article','data'));
    }
}
