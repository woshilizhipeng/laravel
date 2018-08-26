<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\CommonController;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Crypt;

use App\Http\Model\User;

require_once("public/org/code/Code.class.php");

class LoginController extends CommonController
{
    public function login()
    {
        if($input = Input::all() )
        {
            //dd($input);
            $code = new \Code;
            $_code = $code->get();
            if(strtoupper($input['code'])!=$_code)
            {
                 return back()->with('msg','验证码错误!');
            }
            $user = User::first();
            if($user->user_name != $input['username'] || Crypt::decrypt($user->user_pass) != $input['password'])
            {
                return back()->with('msg','用户名或密码错误!');
            }
                session(['user'=>$user]);
                //dd(session('user'));
                return redirect('admin/index');
                
            

                //echo '登录成功';

            

        }else{
            //dd($user);

           return view('admin/login');
        }
    }

     public function quit()
    {
        session(['user'=>null]);
        return redirect('admin/login');
        
    }

    public function code()
    {
        
    	 $code = new \Code;
    	 $code->make();
    	 //echo 2342354;

    }

    public function crypt()
    {
        
        $str = '123456';
        $str_p = "eyJpdiI6IkdvTjkweGlBekNuXC9ObkdiVm1iOTJnPT0iLCJ2YWx1ZSI6IkVTdkwwQmVUODRoVFhpWndmRjhnNXc9PSIsIm1hYyI6IjFhMWE1OGVmMTRjNjdiYzRmNjlmMDVkN2Q0ODFlOThlMTUxZjU4YzE2YzljZDBlMWE4MDU0MDFlN2MzMjIxNjMifQ==";

        echo Crypt::encrypt($str);
        echo '<br/>';
        echo Crypt::decrypt($str_p);
    }
    //public function getcode()
    //{
    //     $code = new \Code;
    //     echo $code->get();
         //echo 2342354;

    //}

}
