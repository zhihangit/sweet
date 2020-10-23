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
        $sql="select * from table order by a.dealstore_id,a.create_time desc limit 10,10";
        echo $sql."</br>";
        echo strpos($sql,"limit")."</br>";
        echo trim(substr($sql,0,strpos($sql,"limit")));
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

    public function baidutest(){
        //114.403513,23.096066 西湖-火车站 114.422629,23.157219
        echo "西湖-火车站两点间距离测试:".GetDistance(23.096066,114.403513,23.157219,114.422629)."</br>";
        $data=getLatLng($address='合生上观国际观涛轩',$city='惠州');
        echo "根据地址获得坐标"."</br>";
        dump($data);
    }
}

