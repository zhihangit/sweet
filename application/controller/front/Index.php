<?php
namespace app\controller\front;
use think\facade\Env;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        //echo  "juse test";
        $this->assign('name', 'thinkphp');
        return $this->fetch('index');
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function demo()
    {

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
}
