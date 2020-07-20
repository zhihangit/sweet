<?php
namespace app\controller\back;
use think\Controller;
use think\facade\Env;

class Demo extends Controller
{
    public function index()
    {
        //echo md5('987001');
        echo Env::get('app_path')."------------------APP_PATH</BR>";
        echo Env::get('ROOT_PATH')."------------------ROOT_PATH</BR>";
        echo Env::get('MODULE_PATH')."------------------MODULE_PATH</BR>";
        echo Env::get('ROUTE_PATH')."------------------ROUTE_PATH</BR>";

        echo config('__PUBLIC__')."------------------PUBLIC目录</BR>";
        echo config('__CSS__')."------------------CSS目录</BR>";
        echo config('__JS__')."------------------JS目录</BR>";
        echo config('__UPLOADS__')."------------------UPLOADS目录</BR>";
        echo config('__IMAGES__')."------------------IMAGES目录</BR>";

        echo __DIR__ . '/../thinkphp/base.php';

    }

    public function classtest(){
        $a=new \my\Test();
        echo $a->sayHello();
    }

    public function delecook(){
        setcookie('remember',1,time()-3600);
        setcookie('olduser_pwd','',time()-3600);
        setcookie('user_pwd','',time()-3600);
        setcookie('user_limite',1,time()-3600);
        setcookie('user_id','',time()-3600);
        setcookie('user_name',1,time()-3600);
        echo "已删除所有cookie";
    }
}

