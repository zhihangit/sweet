<?php
namespace app\controller\front;
use app\model\back\User;
use think\Db;
use think\facade\Env;
use think\Controller;
use think\facade\Request;

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
        if(Request::param('venderid')){
            $venderid=input('venderid');
            $sql="select *,a.id as aid  from sw_user a left join sw_userinfo b on a.id=b.user_id where a.parent_id=$venderid";
            $res=Db::query($sql);
            //dump($res);
            $this->assign('list',$res);
            return $this->fetch('venderexchange');
        }else{
            $this->error('没有此品牌店入驻，请选择页面进驻品牌进行兑换');
        }

    }

    public function exchange(){
        $con['limite']=2;
        $vender=User::with('Userinfo')->where($con)->select();
        //dump($vender);
        $this->assign('vender', $vender);
        return $this->fetch('exchange');

    }
    public function storeexchange(){
        if(Request::param('storeid')){
            $storeid=input('storeid');
            $sql="select *,a.id as aid from sw_user a left join sw_userinfo b on a.id=b.user_id where a.id=$storeid";
            $storeinfo=Db::query($sql);
            $this->assign('storeinfo',$storeinfo);
            //dump($storeinfo);
            $sql="select *,a.id as aid  from sw_storeproduct a left join sw_product b on a.product_id=b.id where b.del_flag=0 and a.on_off=0 and a.store_id=$storeid";

            //echo $sql;
            $res=Db::query($sql);
            //dump($res);
            $this->assign('product',$res);
            return $this->fetch('storeexchange');
        }else{
            $this->error('没有改门店，请重新选择');
        }


    }

    public function confirmorder()
    {
        if (Request::isPost()) {
            $storeid = input('storeid');
            $p_data = (Request::param());
            $totalnum = 0;
            $totalinfo = "";
            foreach ($p_data as $key => $value) {
                if (substr($key, 0, 1) == 'p') {//取订单明细数据
                    if ($value <> 0) {
                        $lookid = substr($key, 1, 1);
                        //echo $key.'|'.substr($key,1,1)."|".$value."</br>";
                        $newdata["$key"] = Db::table('sw_storeproduct')
                            ->alias('a')
                            ->join('sw_product b', 'a.product_id = b.id', 'left')
                            ->where('a.id', $lookid)
                            ->where('a.store_id', $storeid)
                            ->find();
                        $totalnum = $totalnum + $value * $newdata["$key"]['newprice'];
                        $tempstr = $lookid . "|" . $newdata["$key"]['name'] . "|" . $value . "|" . $newdata["$key"]['newprice'];
                        $totalinfo = $totalinfo . "," . $tempstr;
                        $newdata["$key"]['num'] = $value;


                    }

                }
            }
            if (isset($newdata)) {
                //dump($newdata);
                $this->assign('newdata', $newdata);
                $storename = Db::table("sw_userinfo")->getFieldByUser_id($storeid, 'company');
                $this->assign('storeid', $storeid);
                $this->assign('storename', $storename);
                $this->assign("totalnum", $totalnum);
                $this->assign("totalinfo", substr($totalinfo,1));
                return $this->fetch("confirmorder");
            } else {
                $this->error("请选择相关商品，并确定数量不为零进行兑换");
            }
            // echo "订单明细:".substr($totalinfo,1);
            // echo "</br>合计:".$totalnum;

        }else{
            $this->error("非法操作");
        }
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
