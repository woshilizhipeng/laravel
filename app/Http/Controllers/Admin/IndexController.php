<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\User;
use Illuminate\Support\Facades\Crypt;

class IndexController extends CommonController
{
    public function index(){
    	return view('admin/index');

   }

    public function info(){
    	return view('admin/info');
    	//echo 234234;

   }

   public function pass()
   {
   	    
   	    if($input = Input::all())
   	    {
            $rules = [
              'password' =>'required|between:6,20|confirmed',
            ];
            $message = [
              'password.required'=>'新密码不能为空',
              'password.between'=>'新密码必须在6-20位之间',
              'password.confirmed'=>'新密码和确认密码不一致',
          ];

            //dd($input);
   	        $validator = Validator::make($input,$rules,$message);
            //dd($validator);
            if($validator->passes()){
              //echo 'yes';
              $user = User::first();
              //dd($user);
              $_password = Crypt::decrypt($user->user_pass);
              //echo $_password;
              if($input['password_o']==$_password){
                //echo '正确';

               $user->user_pass = Crypt::encrypt($input['password']);
               $user->update();
               return back()->with('errors','密码修改成功');

              }else{
                return back()->with('errors','原密码错误');
              }
            }else{
              return back()->withErrors($validator);
              //dd($validator->errors()->all());
                    
            }
         


            


   	    }else{

    	      return view('admin.pass');
        }

        
    	//echo 234234;
   }
}
