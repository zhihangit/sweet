<?php
namespace app\test\controller;
//use think\Env;
use think\facade\Env;
use think\facade\Config;
class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">我的PHP框架test</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    public function hello($name = 'ThinkPHP5')
    {

    	echo Env::get('app_path');
    	echo "</br>";
        echo Env::get('config_path');
    	echo "</br>";


        echo config('app_debug');
    	echo "</br>";
        echo config('default_timezone');
    	echo "</br>";



        //echo 's'. config('default_return_type').'t';
       // dump(Env::get('DOCUMENT_ROOT'));
        return 'hello,' . $name;
    }
}
