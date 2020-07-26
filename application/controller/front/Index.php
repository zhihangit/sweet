<?php
namespace app\controller\front;
use app\model\back\User;
use think\Db;
use think\facade\Env;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $con['limite']=2;
        $vender=User::with('Userinfo')->where($con)->select();
        //dump($vender);
        $this->assign('vender', $vender);
        $sql="select * from sw_product where main_flag=1 and del_flag=0 order by create_time desc ";

        $product=Db::query($sql);
        $this->assign('product',$product);
        return $this->fetch('index');
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function venderexchange(){

    }

    public function exchange(){

    }
    public function storeexchange(){

    }

    public function singleexchange(){

    }


    public function demo()
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
}
