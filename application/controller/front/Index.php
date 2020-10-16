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
        $sql="select a.*,b.company from sw_product a  left join sw_userinfo b on a.user_id=b.user_id where a.main_flag=1 and a.del_flag=0 order by a.create_time desc ";
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
        //dump($res);
        //die('s');
        $this->assign('product',$res);

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
        //dump(Request::param());
        if (Request::isPost()) {
            $storeid = input('storeid');
            $p_data = (Request::param());
            $totalnum = 0;//商品总数
            $totalfee=0;//总金额
            $totalinfo = "";
            $tkey="";

            foreach ($p_data as $key => $value) {
                if (substr($key, 0, 1) == 'n') {//取订单明细数据
                    $newdata_flag=1;//设置新数据标志
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
                        $newdata["$key"]['ordernum'] = $value;
//echo $newdata["$key"]["name"];
                        $totalnum = $totalnum + $value;//总商品数量
                        $tempstr='';
                        $tempstr = $lookid .  "|" . $value;
                    }else{
                        $newdata_flag=0;//如果商品数量为0则不处理后续2-3条记录
                    }

                }

              if (substr($key, 0, 1) == 'p') {
                  if($newdata_flag==1){
                  $g=explode('-',$value);
                    //echo $g[1];
                  $newdata["$tkey"]['orderprice'] = $g[1];
                  $newdata["$tkey"]['guige'] = $g[0];
                  //dump($newdata);
                  $totalfee=$totalfee+ $newdata["$tkey"]['ordernum']*$g[1];
                  $tempstr =$tempstr."|".$newdata["$tkey"]['orderprice']."|".$newdata["$tkey"]['ordernum']*$g[1]."|".$g[0];
                  //totalinfo格式:商品ID|数量|单价|合计|规格|可选项1|可选项2
                  }
                }

               if (substr($key, 0, 1) == 'x') {
                   if($newdata_flag==1) {
                       $newdata["$tkey"]['orderoption1'] = $value;
                       $tempstr = $tempstr . "|" . $value;
                       //echo $totalinfo."</br>";
                       $totalinfo = $totalinfo . "," . $tempstr;
                       //totalinfo格式:商品ID|数量|单价|合计|规格|可选项1|可选项2  其中可选项2未必有，所以进行单独处理
                       //echo $tempstr."</br>";
                   }
                }



                if (substr($key, 0, 1) == 'v') {
                    if($newdata_flag==1) {
                    $newdata["$tkey"]['orderoption2'] = $value;
                    $totalinfo=$totalinfo."|".$value;
                    //totalinfo格式:商品ID|数量|单价|合计|规格|可选项1|可选项2  其中可选项2未必有，所以进行单独处理
                    }
                }




            }
            //echo $totalinfo;
            if (isset($newdata)) {
                //dump($newdata);
                $this->assign('newdata', $newdata);
                $storename = Db::table("sw_userinfo")->getFieldByUser_id($storeid, 'company');
                $this->assign('storeid', $storeid);
                $this->assign('storename', $storename);
                $this->assign("totalnum", $totalnum);
                $this->assign("totalfee", $totalfee);
                $this->assign("totalinfo", substr($totalinfo,1));
                return $this->fetch("confirmorder");
            } else {
                $this->error("请选择相关商品，并确定数量不为零进行兑换");
            }
        }else{
            $this->error('非法操作',url('front.index/index'));
        }
    }
    //旧方法
    /*public function addorder(){
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
    }*/
    public function addorder(){
         //dump(Request::param());
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
                                $insertdata['grouporderid']=getrandstr1(1).time();
                                $insertdata['codenumber']=input('codenumber');
                                $insertdata['codecompanyid'] = Db::table('sw_codedetail')->getFieldByNumber(input('codenumber'), 'code_id');

                                $insertdata['dealstore_id']=input('storeid');
                                //$insertdata['totalnum']=$sorder[2];
                                $insertdata['dealstorep_id'] = Db::table('sw_user')->getFieldById(input('storeid'), 'parent_id');

                                $insertdata['order_time']=date_create()->format('Y-m-d H:i:s');
                                $insertdata['buyermemo']=input('memo');
                                $insertdata['status']=1;
                               // $insertdata['sellermemo']='';
                                //$insertdata['expressid']='';
                                if(isset($_POST['takeself']) &&$_POST['takeself'] == '1')
                                {
                                    $insertdata['type']=1;
                                    $insertdata['pickname']=input('ordercontact');
                                    $insertdata['picktel']=input('ordertel');
                                }
                                else{
                                    $insertdata['type']=2;
                                    $insertdata['dispatchname']=input('ordercontact');
                                    $insertdata['dispatchtel']=input('ordertel');
                                    $insertdata['dispatchaddress']=input('orderaddress');
                                }
                                //$insertdata['create_time']=date_create()->format('Y-m-d H:i:s');
                                 $arroder=explode(",",$sorder[0]);

                                foreach ($arroder as $key=> $value ){
                                    $orderarr = (array) null;
                                    $orderarr = $insertdata;
                                    $o=explode('|',$value);
                                    //商品ID|数量|单价|合计|规格|可选项1|可选项2  其中可选项2未必有，所以进行单独处理
                                    //$ss=$ss." 商品[".$o[1]."]数量[".$o[2]."]单价[".$o[3]."];";
                                    $orderarr['order_id']="S".$orderarr['grouporderid'].$key;
                                    //这里的id是主店添加商品的id而非门店的商品id
                                    $orderarr['product_id']=Db::table('sw_storeproduct')->getFieldById($o[0], 'product_id');
                                    $orderarr['product']=Db::table('sw_product')->getFieldById($orderarr['product_id'], 'name');
                                    $orderarr['num']=$o[1];
                                    $orderarr['price']=$o[2];
                                    $orderarr['total']=$o[3];
                                    $orderarr['specification']=$o[4];
                                    $orderarr['orderoption1']=$o[5];
                                    if(isset($o[6])){
                                        $orderarr['orderoption2']=$o[6];
                                    }
                                    //dump($orderarr);
                                    //die("stop");
                                    $dorder = new \app\model\back\Neworder;
                                    $res=$dorder->save($orderarr);
                                   // $oid = $dorder->id;
                                }
                            if($res){
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
/* 旧订单查询   public function searchcode(){

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
        return $this->fetch("searchcode");
  }*/
    public function searchcode(){

        $code=trim(input("codenumber"));
        if($code=='' or  empty($code)){
            $this->error("请输入兑换码信息，再查询",'front.index/index');
        }
        $sql="select a.*,b.company from sw_neworder a left join sw_userinfo b on a.dealstore_id=b.user_id where a.codenumber='$code' order by a.create_time desc ";
        $datainfo=Db::query($sql);
        if(!$datainfo){
            $this->error("没有该兑换码信息，请重新确认",'front.index/index');
        }
        $this->assign('datainfo',$datainfo);
        $ye=Db::table("sw_codedetail")->getFieldByNumber($code,"balance");
        $this->assign('ye',$ye);
        $code_id=Db::table("sw_codedetail")->getFieldByNumber($code,"code_id");
        $indate=Db::table("sw_code")->getFieldById($code_id,"indate");
        $this->assign('indate',$indate);
        return $this->fetch("searchcode");
    }
    public function dealorder(){

}

    public function productdetail(){

        $aid=input("aid");
        //echo $aid;
        $id=Db::table("sw_storeproduct")->getFieldById($aid,'product_id');
        $pdata=Db::table("sw_product")->where('id',$id)->find();
        //$pimage=Db::table("sw_productimage")->where('product_id',$id)->select();
        $pimage=Db::table("sw_productimage")->where('product_id',$id)->order('id')->select();
        $pdata['pricesystem']=explode('|',$pdata["pricesystem"]);
        foreach ($pdata['pricesystem'] as $key=>$value){
            $pdata['pricesystem'][$key]= explode('/',$value);
        }
        $this->assign("pdata",$pdata);
        $this->assign("pimage",$pimage);
        //dump($pdata);
        return $this->fetch("productdetail");
        //dump($pimage);

}
    /*public function nostoreidproductdetail(){

        $aid=input("aid");
        $pdata=Db::table("sw_product")->where('id',$aid)->find();
        $pimage=Db::table("sw_productimage")->where('product_id',$aid)->order('id')->select();
        $pdata['pricesystem']=explode('|',$pdata["pricesystem"]);
        foreach ($pdata['pricesystem'] as $key=>$value){
            $pdata['pricesystem'][$key]= explode('/',$value);
        }
        $this->assign("pdata",$pdata);
        $this->assign("pimage",$pimage);
        return $this->fetch("nostoreidproductdetail");
    }*/
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
