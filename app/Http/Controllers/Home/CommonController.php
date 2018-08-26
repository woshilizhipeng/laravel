<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Model\Navs;
use Illuminate\Support\Facades\View;
use App\Http\Model\Article;




class CommonController extends Controller
{
    public function __construct()
    {
    	//最新文章八篇
        $new = Article::orderBy('art_time','desc')->take(8)->get();
        $hotRight = Article::orderBy('art_view','desc')->take(5)->get();
    	//dd($hotRight);
         //echo '232324';
    	$navs = Navs::orderBy('nav_order','asc')->get();
    	View::share('navs',$navs);
    	View::share('new',$new);
    	View::share('hotRight',$hotRight);
    }
}
