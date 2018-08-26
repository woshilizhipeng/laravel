<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use DB;
use App\Http\Model\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    //get.admin/config   全部配置项列表 
    public function index()
    {
    	//echo '全部配置项列表';
        //dd('234234');
    	$data = Config::orderBy('conf_order','asc')->get();
        //dd($data);
        foreach($data as $k=>$v){
            //echo $v;
            switch($v->field_type){
                case 'input':
                    $data[$k]->_html = '<input type="text" class="lg" name="conf_content[]" value="'.$v->conf_content.'">';
                    //echo $data[$k]->_html;
                    break;
                case 'textarea':
                    $data[$k]->_html = '<textarea type="text" class="lg" name="conf_content[]">'.$v->conf_content.'</textarea>';
                    //echo $data->_html;
                    break;
                case 'radio':
                    //echo $v->field_value;
                    $arr = explode(',',$v->field_value);
                    //dd($arr);
                    $str = '';
                    foreach($arr as $m=>$n){
                        $r = explode('|',$n);
                        //dd($r );
                        $c = $v->conf_content==$r[0]?'checked':'';
                        $str.= '<input type="radio" name="conf_content[]" value="'.$r[0].'" '.$c.'>'.$r[1].'  ';

                    }
                    //echo $str;
                    $data[$k]->_html = $str;
                    break;
            }
        }

    	return view('admin/config/index',compact('data'));
    }
    //修改排序
    //post.admin/config/changeorder
    public function changeOrder()
    {
    	//echo 234234;
    	$input = Input::all();
    	//dd($input);
    	$conf = Config::find($input['conf_id']);
    	$conf->conf_order = $input['conf_order'];
    	$re = $conf->update();
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

    //get.admin/config/{Config}  显示个人分类信息
    public function show()
    {
    	
    }

    //get.admin/config/create   添加配置项
    public function create()
    {
    	//echo '添加配置项';
        return view('admin/config/add');
    }

    //post.admin/config   添加配置项提交
    public function store()
    {
    	$input = Input::except('_token');
    	//dd($input);
    	$rules = [
              'conf_name' =>'required',
              'conf_title' =>'required',
            ];
            $message = [
              'conf_name.required'=>'配置项名称不能为空',
              'conf_title.required'=>'配置项标题不能为空',
             
          ];

            //dd($input);
   	        $validator = Validator::make($input,$rules,$message);
            //dd($validator);
            if($validator->passes()){
               $re = Config::create($input);
               //dd($re);
               if($re){
                 $this->putFile();

               	 return redirect('admin/config');
               }else{
               	return back()->with('errors','配置项失败');
               }
            }else{
              return back()->withErrors($validator);
              //dd($validator->errors()->all());
                    
            }
    }
      
     //get.admin/config/{config}/edit  编辑配置项
    public function edit($conf_id)
    {
    	//echo $cate_id;
    	$field = Config::find($conf_id);
    	//dd($field);
    	
    	return view('admin.config.edit',compact('field'));
    }

     //put.admin/config/{config}   提交更新配置项
    public function update($conf_id)
    {
    	//dd(Input::all());
    	$input = Input::except('_token','_method');
    	//dd($input);
    	$re = Config::where('conf_id',$conf_id)->update($input);
    	//dd($re);
    	if($re){
            $this->putFile();
            return redirect('admin/config');
    	}else{
            return back()->with('errors','配置项更新失败');
    	}

    }

    //delete.admin/config/{config}   删除单个配置信息
    public function destroy($conf_id)
    {
        $re = Config::where('conf_id',$conf_id)->delete();
        
        if($re){
            $this->putFile();
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

    public function changeContent()
    {
        //echo '234234';
        //dd(Input::all());
        $input = Input::all();
        foreach($input['conf_id'] as $k=>$v){
            //echo $v;
            Config::where('conf_id',$v)->update(['conf_content'=>$input['conf_content'][$k]]);

        }
        $this->putFile();
        return back()->with('errors','配置项更新成功');
    }
    //写入配置文件
    public function putfile()    
    {
        //echo \Illuminate\Support\Facades\Config::get('');
        $config = Config::pluck('conf_content','conf_title')->all();
        //echo var_export($config,true);
        //dd($config);
        $path = base_path().'\config\web.php';
        
        $str = '<?php return '.var_export($config,true).';';
        file_put_contents($path,$str);
        //echo $path;


    }


}
