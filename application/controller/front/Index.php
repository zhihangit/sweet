<?php
namespace app\controller\front;
use app\model\back\User;
use app\model\front\Order;
use app\model\front\Orderdetail;
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
            if(Request::param('pid')){
                $pid=Request::param('pid');
            }else{$pid="";}
          //pid这里是客户点击了单个商品传进的商品id
            if($pid<>""){
                $sql="select *,a.id as aid  from sw_storeproduct a left join sw_product b on a.product_id=b.id where b.del_flag=0 and a.on_off=0 and a.store_id=$storeid and a.product_id=$pid" ;
            }else
            {
            $sql="select *,a.id as aid  from sw_storeproduct a left join sw_product b on a.product_id=b.id where b.del_flag=0 and a.on_off=0 and a.store_id=$storeid";
            }

            //echo $sql;
            $res=Db::query($sql);

            foreach ($res as $key=> $value){
                //echo  $res[$key]["pricesystem"];
                $free=explode("/",$value["freeoption1"]);
                $res[$key]["freeoption1"]=$free;
                $free=explode("/",$value["freeoption2"]);
                $res[$key]["freeoption2"]=$free;
                //echo count($res[$key]["freeoption2"]);
                $dp=explode("|",$value["pricesystem"]);
                $dpinf = (array) null;

                foreach ($dp as $key2 =>$value2){
                      $dpinfo[$key2]=explode("/",$value2);
                }
                $res[$key]["pricesystem"]=$dpinfo;
            //dump($dpinfo);
            }
            //foreach ($res as $value)
            //{dump($value);}
            $this->assign('product',$res);
            return $this->fetch('storeexchange');
        }else{
            $this->error('没有该门店，请重新选择');
        }


    }
    public function confirmorder()

    {
        dump(Request::param());
        if (Request::isPost()) {
            $storeid = input('storeid');
            $p_data = (Request::param());
            $totalnum = 0;
            $totalinfo = "";
            $tkey="";
            foreach ($p_data as $key => $value) {
                if (substr($key, 0, 1) == 'n') {//取订单明细数据
                    $tkey=$key;
                    if ($value <> 0) {
                        $lookid = substr($key, 1);
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

           /*     if (substr($key, 0, 1) == 'p') {
                    $g=explode('-',$value);
                    $newdata["$tkey"]['newprice'] = $g[1];
                    $newdata["$tkey"]['guige'] = $g[0];
                }*/

                if (substr($key, 0, 1) == 'x') {
                    $newdata["$tkey"]['option1'] = $value;
                }



                if (substr($key, 0, 1) == 's') {
                    $newdata["$tkey"]['option2'] = $value;
                }
            }
            if (isset($newdata)) {
                dump($newdata);
                $this->assign('newdata', $newdata);
                $storename = Db::table("sw_userinfo")->getFieldByUser_id($storeid, 'company');
                $this->assign('storeid', $storeid);
                $this->assign('storename', $storename);
                $this->assign("totalnum", $totalnum);
                $this->assign("totalinfo", substr($totalinfo,1));
               // return $this->fetch("confirmorder");
            } else {
                $this->error("请选择相关商品，并确定数量不为零进行兑换");
            }
        }else{
            $this->error('非法操作',url('front.index/index'));
        }
    }
    public function addorder(){
       // dump(Request::param());
        if(Request::isPost()){
            //$r兑换码本身信息
            $r=Db::table('sw_codedetail')->where('number',input('codenumber'))->find();
            if($r){
                if(input('proof')<>$r['proof']){
                    $this->error('兑换码对应验证码不对，请重新兑换',url('front.index/storeexchange',['storeid'=>input('storeid')]));
                }else{
                    //$c兑换码对应批次信息
                    $c=Db::table('sw_code')->where('id',$r['code_id'])->find();
                    if($c['status']<>2){
                        $this->error('该兑换码未激活或已作废',url('front.index/index'));
                    }else{
                        $endtime=strtotime($c['indate']);
                        $nowtime = time();
                        if($nowtime > $endtime){
                            $this->error('该兑换码已过期',url('front.index/index'));
                        }else{
                            $s=Request::param();
                            $sorder=explode('^',$s["totalinfo"]);
                            if ($sorder[2]>$r['balance']){
                                $this->error('余额不足，请重新兑换',url('front.index/storeexchange',['storeid'=>input('storeid')]));
                            }else{
                                $insertdata['codenumber']=input('codenumber');
                                $insertdata['storeid']=input('storeid');
                                $insertdata['totalnum']=$sorder[2];
                                $insertdata['storepid'] = Db::table('sw_user')->getFieldById(input('storeid'), 'parent_id');
                                $insertdata['codecompanyid'] = Db::table('sw_codedetail')->getFieldByNumber(input('codenumber'), 'code_id');

                                $insertdata['ordercontact']=input('ordercontact');
                                $insertdata['ordertel']=input('ordertel');
                                $insertdata['memo']=input('memo');
                                $insertdata['result']='';
                                $insertdata['expressid']='';
                                //$insertdata['create_time']=date_create()->format('Y-m-d H:i:s');
                                $insertdata['orderinfo']=explode(",",$sorder[0]);
                                $ss="订单商品明细：";
                                $i=0;
                                foreach ($insertdata['orderinfo'] as $value ){

                                    $o=explode('|',$value);
                                    $ss=$ss." 商品[".$o[1]."]数量[".$o[2]."]单价[".$o[3]."];";
                                    $orderdetail[$i]['product']=$o[1];
                                    $orderdetail[$i]['num']=$o[2];
                                    $orderdetail[$i]['price']=$o[3];
                                    $i=$i+1;

                                }
                               // dump($orderdetail);
                                $insertdata['productinfo']=$ss;
                                if(isset($_POST['takeself']) &&$_POST['takeself'] == '1')
                                {
                                    $insertdata['takeself']=1;
                                    $insertdata['orderaddress']='';
                                }
                                else{
                                    $insertdata['takeself']=0;
                                    $insertdata['orderaddress']=input('orderaddress');
                                }

                                $dorder = new \app\model\front\Order;
                                $res=$dorder->save($insertdata);
                                $oid = $dorder->id;


                                if($res){
                                    foreach ($orderdetail as $value ){
                                       $ddata=$value;
                                       $ddata['order_id']=$oid;
                                       $dorderdetial=new Orderdetail;
                                       $dorderdetial->save($ddata);
                                    }


                                    $this->success('已提交兑换订单，等待商家处理', url('front.index/searchcode',['codenumber'=>input('codenumber')]));
                                }else{
                                    $this->error('兑换失败异常，请重新兑换',url('front.index/storeexchange',['storeid'=>input('storeid')]));
                                }
                            }
                        }
                    }

                }
            }else{
                $this->error('没有该兑换码信息，请重新兑换',url('front.index/storeexchange',['storeid'=>input('storeid')]));
            }


        }else{
            $this->error('非法操作',url('front.index/index'));

        }
    }
    public function singleexchange(){

        if(Request::param('id')){

            $venderid=Db::table("sw_product")->getFieldById(Request::param('id'),'user_id');
            $sql="select *,a.id as aid  from sw_user a left join sw_userinfo b on a.id=b.user_id where a.parent_id=$venderid";
            $res=Db::query($sql);
            //dump($res);
            $this->assign('list',$res);
            $this->assign('pid',Request::param('id'));
            return $this->fetch('singleexchange');
        }else{
            $this->error('没有此品牌店入驻，请选择页面进驻品牌进行兑换');
        }



    }
    public function searchcode(){

        $code=trim(input("codenumber"));
        $sql="select a.*,b.company from sw_order a left join sw_userinfo b on a.storeid=b.user_id where a.codenumber='$code' order by a.create_time desc ";
        $datainfo=Db::query($sql);
        if(!$datainfo){
            $this->error("没有该兑换码信息，请重新确认");
        }
        $this->assign('datainfo',$datainfo);
        $ye=Db::table("sw_codedetail")->getFieldByNumber($code,"balance");
        $this->assign('ye',$ye);
        $code_id=Db::table("sw_codedetail")->getFieldByNumber($code,"code_id");
        $indate=Db::table("sw_code")->getFieldById($code_id,"indate");
        $this->assign('indate',$indate);
/*        if(!$datainfo){
            $this->error("没有该兑换码信息，请重新确认");
        }else{
            $this->assign('datainfo',$datainfo);
            $ye=Db::table("sw_codedetail")->getFieldByNumber($code,"balance");
            $this->assign('ye',$ye);

        }*/
        return $this->fetch("searchcode");
  }
    public function dealorder(){

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
    public function test(){
        return $this->fetch('test');
    }
}
