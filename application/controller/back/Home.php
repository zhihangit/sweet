<?php
namespace app\controller\back;
use app\model\back\Product;
use app\model\back\Userinfo;
use app\model\front\Order;
use app\model\front\Orderdetail;
use think\facade\Env;
use think\Db;
use think\facade\Request;
use think\Controller;
use app\model\back\User;
use app\model\back\Code;
use app\model\back\Client;
use app\model\back\Region;
use app\model\back\Neworder;
use think\paginator\driver\Bootstrap;
use PHPExcel;
use PHPExcel_IOFactory;//引入两个类
class Home extends Controller
{
    protected $beforeActionList = [
        'judge'=>  ['except'=>'loginout'],
    ];
    //安全考虑
    protected  function judge(){
        // if (!isset(cookie('user_name')) or !isset(cookie('user_id')) )
        if (!isset($_COOKIE['user_name']) or !isset($_COOKIE['user_id']) )
        {
            $this->redirect(url('back.login/index'));
        }
    }
    public function index()
    {
        //echo  "juse test";
        $this->assign('todaytime', time());

        // echo  "juse  home test";

        return $this->fetch('index');
    }
    public function modipwd(){
        return $this->fetch('modipwd');
    }
    public function domodipwd(){
        $passwd=Db::table('sw_user')->getFieldById(cookie('user_id'),'userpwd');
        if($passwd<>md5(input('oldpwd'))){
            $this->error("原密码不对，请重新输入");
        }

        if (input('newpwd')<>input('confirmnewpwd')){
            $this->error("两次输入新密码不一致，请重新输入");
        }else{
            $modidata['userpwd']=input('newpwd');
        }
        if(Request::isPost()){
            $validate = new \app\validate\back\Vuserpwd;
            if (!$validate->check($modidata)) {
                dump($validate->getError());
            }else{
                $modidata['userpwd']=md5($modidata['userpwd']);
                $user = User::get(cookie('user_id'));
                $r=$user->save($modidata);
                if($r){
                    $this->success('修改成功');
                }
                else{
                    $this->success('修改失败');
                }

            }
        }

    }
    //退出系统
    public function loginout()
    {
        cookie('user_id', null);
        cookie('user_limite', null);
        if(!isset($_COOKIE['remember'])){
            cookie('user_name', null);
        }
        //dump($_COOKIE);
        $this->redirect(url('back.login/index'));
    }
    //商家管理
    public function comadmin()
    {
        return $this->fetch('comadmin');
    }
    public function vendermanage(){
        $con['limite']=2;
        $con['parent_id']=1;
        $list=User::with('Userinfo')->where($con)->select();
        $this->assign('list',$list);
        //$list= User::with(['Userinfo' => function($query) { $query->field('user_id,company,email');}])->select();
        // dump($list);
        return $this->fetch('vendermanage');
    }
    public function modivender(){
        if (Request::has('id'))
        {
            $modid = input('id');
            $user = User::get($modid );
            //echo $user->userinfo->email;
            //dump($user);
            $userareaname=Region::where('id',$user->area)->value('name');
            $p['id']=Region::where('id',$user->area)->value('parent_id');
            $p['name']=Region::where('id',$p['id'])->value('name');
            $pp['id']=Region::where('id',$p['id'])->value('parent_id');
            // $pp['name']=Region::where('id',$p['id'])->value('name');

            $region = Db::name('region')->where(['level_type' => 1])->select();
            $this->assign('userareaname',$userareaname);
            $this->assign('region', $region);
            $this->assign('user',$user);
            $this->assign('p',$p);
            $this->assign('pp',$pp);
            return $this->fetch('modivender');
            //echo $pid."$".$ppid;
        }
        else{

            $this->error('非法操作');

        }

    }
    public function domodivender(){
        $flag=false;
        $modidata=Request::param();
        $modidata['parent_id']=cookie('user_id');

        //如果没有设置新密码则保留旧密码
        if ($modidata['userpwd']==''){
            $flag=true;//设置密码保留标志
            $modidata['userpwd']=Db::table('sw_user')->getFieldById(input('userid'), 'userpwd');
        }
        // die('stop here');
        $isfile=$_FILES;
        if($isfile['info_photo']['name']==''){
            $vimage = Db::table('sw_userinfo')->getFieldByUser_id(input('userid'), 'image');
        }
        else{
            $file = request()->file('info_photo');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->move( './uploads');
            if($info){
                $vimage=$info->getSaveName();
            }else
            { $this->error('图片上传失败！');}
        }
        if(Request::isPost()){
            $validate = new \app\validate\back\Vuser;
            if (!$validate->check($modidata)) {
                dump($validate->getError());
            }else{
                $user = User::get($modidata['userid']);
                $user->username=$modidata['username'];
                if(cookie('limite')==2){
                    $modidata['limite']=3;
                }
                $user->limite= $modidata['limite'];//品牌店仅可以添加门店
                if($flag==false){
                    $user->userpwd=md5($modidata['userpwd']);
                }
                $user->userinfo->adword=input('adword');
                $user->userinfo->email=input('email');
                $user->userinfo->company=input('company');
                $user->userinfo->address=input('address');
                $user->userinfo->image=$vimage;
// 更新当前模型及关联模型
                $res=$user->together('userinfo')->save();
                if($res){

                    $this->success('修改成功', 'back.home/vendermanage');
                }else{
                    $this->error('修改失败');
                }
            }

        }else
        {
            $this->error('非法操作');
        }


    }
    public function addvender(){
        if (Request::isPost()) {
            $data = Request::param();
            $id = $data['pro_id'];
            $region = Db::name('region')->where(['parent_id' => $id])->select();

            $opt = '<option>--请选择--</option>';
            foreach($region as $key=>$val){
                $opt .= "<option value='{$val['id']}'>{$val['name']}</option>";
            }
            echo json_encode($opt);
            die;
        }

        $region = Db::name('region')->where(['level_type' => 1])->select();
        $this->assign('region', $region);

        return $this->fetch('addvender');
    }
    public function doaddvender()
    {
        if(Request::isPost())
        {
            $validate = new \app\validate\back\Vuser;
            if (!$validate->check(Request::param()))
            {
                dump($validate->getError());
            }else
            {
                $userdata=Request::param();
                $userdata['parent_id']=cookie('user_id');
                $userdata['userpwd']=md5(input('userpwd'));
                if(cookie('user_limite')==2){
                    $userdata['limite']=3;
                }
                //dump($userdata);
                //die('stop');
                $file = request()->file('info_photo');
                // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move( './uploads');
                if($info){
                    $userdata['image']=$info->getSaveName();
                }else
                { $this->error('图片上传失败！');}

                $mod = new User();
                //品牌店仅可以添加门店

                $modinfo=new Userinfo();
                $modinfo->adword=$userdata['adword'];
                $modinfo->email=$userdata['email'];
                $modinfo->address=$userdata['address'];
                $modinfo->company=$userdata['company'];
                $modinfo->image=$userdata['image'];
                $modinfo->contact=$userdata['contact'];
                $modinfo->tel=$userdata['tel'];
                $modinfo->latitude=$userdata['latitude'];
                $modinfo->longitude=$userdata['longitude'];

                $mod->userinfo=$modinfo;
                $a=$mod->allowField(['username','userpwd','limite','area','parent_id'])->together('userinfo')->save($userdata);

                if(false === $a){
                    // 验证失败 输出错误信息
                    dump($mod->getError());
                    die;
                }else
                {
                    //die('stop here');
                    //echo $mod->id;
                    //sleep("6");
                    $this->success('新增成功', 'back.home/vendermanage');
                }
            }

        }else
        {
            $this->error('非法操作');
        }



    }
    public function delevender()
    {
        if (Request::has('id')) {
            $delid = input('id');
            //User::destroy($delid);
            //Userinfo::destroy($delid);

            $data = User::get($delid,'userinfo');
            // 删除当前及关联模型
            $data->together('userinfo')->delete();
            //die('stop here');
            $this->success('删除成功', 'back.home/vendermanage');
        } else {
            $this->error('非法操作');
        }
    }
    public function codemanage(){
        $list = Code::withJoin([
            'client'    =>  ['id', 'companyname']
        ],'LEFT')->order('create_time desc')->select();
        $this->assign('list',$list);

        return $this->fetch('codemanage');
    }
    public function addcode(){
        $clientlist = Db::name('client')->order('companyname')->select();
        $this->assign('clientlist', $clientlist);

        return $this->fetch('addcode');
    }
    public function doaddcode()
    {

        $isfile=$_FILES;
        $userdata=Request::param();
        if(Request::isPost())
        {
            $validate = new \app\validate\back\Vcode;
            if (!$validate->check(Request::param()))
            {
                dump($validate->getError());
            }else
            {
                if($isfile['contract']['name']==''){
                    $userdata['contract']='';
                }else{

                    $file = request()->file('contract');
                    // 移动到框架应用根目录/uploads/ 目录下
                    $info = $file->move( './uploads');

                    if($info){
                        $userdata['contract']=$info->getSaveName();
                    }else
                    { $this->error('合同上传失败！');}
                }

                $userdata['status']=1;
                $mod = new Code();
                //dump($userdata);
                //die('stop!');
                $a=$mod->save($userdata);
                $maxid=$mod->id;
                if(false === $a){
                    // 验证失败 输出错误信息
                    dump($mod->getError());
                    die;
                }else
                {
                    $b=input('amount');
                    $balance=$userdata['price'];
                    for ($x=0; $x<$b; $x++) {
                        $number= getrandstr1(8);
                        $proof=getrandstr2();
                        $codedata = ['code_id' => $maxid, 'number' =>$number,'proof'=>$proof,'balance'=>$balance,'create_time'=>date('Y-m-d H:i:s',time())];
                        Db::name('codedetail')
                            ->data($codedata)
                            ->insert();

                        //echo "数字是：$x <br>";

                    }
                    $this->success('新增批次消费码成功', 'back.home/codemanage');
                }
            }

        }else
        {
            $this->error('非法操作');
        }



    }
    public function abolishcode(){

        if (Request::has('id')) {
            $delid = input('id');
            $code = Code::get($delid);
            $code->status= 3;
            $code->save();
            //echo $code->getLastSql();
            // die('stop here');
            $this->success('改批次已作废', 'back.home/codemanage');
        } else {
            $this->error('非法操作');
        }

    }
    public function activatecode(){

        if (Request::has('id')) {
            $activateid = input('id');
            $code = Code::get($activateid);
            $code->status= 2;
            $code->save();
            //echo $code->getLastSql();
            // die('stop here');
            $this->success('改批次已激活', 'back.home/codemanage');
        } else {
            $this->error('非法操作');
        }

    }
    public function modicode(){
        $data = Code::get(input('id'));
        //echo $data['status'];
        if ($data['status']==1) {
            $clientlist = Db::name('client')->select();
            $this->assign('clientlist', $clientlist);
            $this->assign('data',$data);
            return $this->fetch('modicode');
        }else{
            $this->error('已激活或作废批次无法进行修改！');
        }

    }
    public function domodicode(){
        $isfile=$_FILES;
        $userdata=Request::param();
        if(Request::isPost())
        {
            $validate = new \app\validate\back\Vcode;
            if (!$validate->check(Request::param()))
            {
                dump($validate->getError());
            }else
            {
                if($isfile['contract']['name']==''){
                    $userdata['contract']=Db::table('sw_code')->getFieldById(input('id'), 'contract');
                }else{

                    $file = request()->file('contract');
                    // 移动到框架应用根目录/uploads/ 目录下
                    $info = $file->move( './uploads');

                    if($info){
                        $userdata['contract']=$info->getSaveName();
                    }else
                    { $this->error('合同上传失败！');}
                }

                $userdata['status']=1;
                $mod = Code::get(input('id'));
                $a=$mod->save($userdata);
                //dump($userdata);
                //die('stop !');
                if(false === $a){
                    // 验证失败 输出错误信息
                    dump($mod->getError());
                    die;
                }else
                {
                    $b=input('amount');
                    $balance=$userdata['price'];
                    //Db::table('think_user')->where('id',1)->delete();
                    Db::table('sw_codedetail')->where('code_id',input('id'))->delete();
                    //die('stop!!');
                    for ($x=0; $x<$b; $x++) {
                        $number= getrandstr1(8);
                        $proof=getrandstr2();
                        $codedata = ['code_id' => input('id'), 'number' =>$number,'proof'=>$proof,'balance'=>$balance,'create_time'=>date('Y-m-d H:i:s',time())];
                        Db::table('sw_codedetail')
                            ->data($codedata)
                            ->insert();


                    }
                    $this->success('修改成功', 'back.home/codemanage');
                }
            }

        }else
        {
            $this->error('非法操作');
        }



    }
    public function out(){
        require_once Env::get('ROOT_PATH').'public/static/PHPExcel/Classes/PHPExcel.php';
        require_once Env::get('ROOT_PATH').'public/static/PHPExcel/Classes/PHPExcel/IOFactory.php';
        //导出
        $path = dirname(__FILE__); //找到当前脚本所在路径
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);


        // 实例化完了之后就先把数据库里面的数据查出来
        $sql =Db::table('sw_codedetail')
            ->where('code_id',input('id'))

            ->select();

        // 设置表头信息
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '批次号  ')
            ->setCellValue('C1', '兑换码')
            ->setCellValue('D1', '验证码')
            ->setCellValue('E1', '余额')
            ->setCellValue('F1', '创建时间');
        /*--------------开始从数据库提取信息插入Excel表中------------------*/

        $i=2;  //定义一个i变量，目的是在循环输出数据是控制行数
        $count = count($sql);  //计算有多少条数据
        for ($i = 2; $i <= $count+1; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $sql[$i-2]['id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $sql[$i-2]['code_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $sql[$i-2]['number']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $sql[$i-2]['proof']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $sql[$i-2]['balance']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $sql[$i-2]['create_time']);
        }


        /*--------------下面是设置其他信息------------------*/

        $objPHPExcel->getActiveSheet()->setTitle('兑换码明细');      //设置sheet的名称
        $objPHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');   //通过PHPExcel_IOFactory的写函数将上面数据写出来

        $PHPWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel,"Excel2007");

        header('Content-Disposition: attachment;filename="兑换码明细.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件*/

    }
    public function clientmanage(){
        $clientdata=Db::table('sw_client')->order('create_time')->select();
        $this->assign('clientdata',$clientdata);
        return $this->fetch('clientmanage');
    }
    public function addclient(){
        return $this->fetch('addclient');
    }
    public function doaddclient(){
        $userdata=Request::param();
        // dump($userdata);
        if(Request::isPost())
        {
            $client=new Client();

            $a=$client->allowField(true)->save($userdata);

            if(false === $a){
                dump($mod->getError());
                die;
            }else
            {
                $this->success('新增客户成功', 'back.home/clientmanage');
            }


        }else
        {
            $this->error('非法操作');
        }


    }
    public function modiclient(){
        $clientdata= Client::get(input('id'));
        $this->assign('clientdata',$clientdata);
        return $this->fetch('modiclient');
    }
    public function domodiclient(){
        $newdata=Request::param();
        $companyname=Db::table('sw_client')->getFieldById(input('id'),'companyname');
        $newdata['companyname']=$companyname;
        //dump($newdata);
        //die(1);
        $client=Client::get(input('id'));
        $res=$client->save($newdata);
        if($res){
            $this->success('修改成功','back.home/clientmanage');
        }else{
            $this->error('修改失败');
        }


    }
    public function productmanage(){
        $product=new Product();
        $con['del_flag']=0;
        $con['user_id']=cookie('user_id');
        $list=$product->where($con)->order('main_flag desc,create_time')->select();
        //dump($list);
        $this->assign('list',$list);

        return $this->fetch('productmanage');
    }
    public function addproduct(){
        return $this->fetch('addproduct');
    }
    public function doaddproduct(){
        //dump(Request::param());
        //die('s');
        $isfile=$_FILES;
        if($isfile['info_photo']['name']==''){
            $this->error('添加商品必须上传商品主图');

        }else{
            if(Request::isPost())
            {
                $validate = new \app\validate\back\Vproduct;
                if (!$validate->check(Request::param()))
                {
                    dump($validate->getError());
                }else
                {
                    $userdata=Request::param();

                    $userdata['user_id']=cookie('user_id');
                    if(isset($userdata['main_flag'])){
                        $userdata['main_flag']=1;
                    }else{$userdata['main_flag']=0;}
                    //dump($userdata);
                    //die('2 step');
                    $file = request()->file('info_photo');
                    //dump($file);
                    // die('stop');
                    // 移动到框架应用根目录/uploads/ 目录下
                    $info = $file->move( './uploads');
                    if($info){
                        $userdata['mainimage']=$info->getSaveName();
                    }else
                    { $this->error('图片上传失败！');}

                    $mod = new Product();
                    $a=$mod->save($userdata);

                    if(false === $a){
                        // 验证失败 输出错误信息
                        dump($mod->getError());
                        die;
                    }else
                    {
                        ;
                        $this->success('新增成功', 'back.home/productmanage');
                    }
                }

            }else
            {
                $this->error('非法操作');
            }
        }


    }
    public function deleproduct(){
        $id=input('id');
        //预留订单查看，如果该产品尚存未处理订单，则不能做删除标志删除
        $p = Product::get($id);
        $p->del_flag = '1';
        $r=$p->save();
        //echo $p->getlastsql();
        //die('stop');
        if($r){
            $this->success('删除成功','back.home/productmanage');
        }else{
            $this->error('删除失败');
        }

    }
    public function modiproduct(){
        $productdata=Product::get(input('id'));
        $this->assign('productdata',$productdata);
        return $this->fetch('modiproduct');
    }
    public function domodiproduct(){
        $userdata=Request::param();
        $isfile=$_FILES;
        if($isfile['info_photo']['name']==''){
            $userdata['mainimage']=Db::table('sw_product')->getFieldById(input('id'),'mainimage');
        }else{
            $file = request()->file('info_photo');

            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->move( './uploads');
            if($info){
                $userdata['mainimage']=$info->getSaveName();
            }else
            { $this->error('图片上传失败！');}}

        if(Request::isPost())
        {
            $validate = new \app\validate\back\Vproduct;
            if (!$validate->check(Request::param()))
            {
                dump($validate->getError());
            }else
            {
                $userdata['user_id']=cookie('user_id');
                if(isset($userdata['main_flag']))
                {
                    $userdata['main_flag']=1;
                }else
                {$userdata['main_flag']=0;}

                $mod = Product::get(input('id'));
                $a=$mod->save($userdata);

                if(false === $a){
                    // 验证失败 输出错误信息
                    dump($mod->getError());
                    die;
                }else
                {
                    $this->success('修改成功', 'back.home/productmanage');
                }
            }

        }else
        {
            $this->error('非法操作');
        }
    }
    public function uploadimage(){
        $imgdata=Db::table("sw_productimage")->where('product_id',input('id'))->order('id')->select();
        //dump($imgdata);
        $this->assign('imgdata',$imgdata);
        $this->assign('prodcutid',input('id'));

        return $this->fetch('uploadimage');
    }
    public function douploadimage()
    {
        $uploaddir_true =Env::get('ROOT_PATH'). 'public/uploads/productimage/';//物理路径
        $uploaddir_data ='/productimage/';//数据库路径
        $name = $_FILES['file']['name'];
        $uploadfile = $uploaddir_true . $name;
        $type = strtolower(substr(strrchr($name, '.'), 1));
//获取文件类型
        $typeArr = array("jpg","png","gif");
        if (!in_array($type, $typeArr)) {
            echo "请上传jpg,png或gif类型的图片！";
            exit;
        }
        $imageode=getrandstr1(5);
        $targetfile_true=$uploaddir_true .$imageode. $_FILES['file']['name'];
        $targetfile_data=$uploaddir_data .$imageode. $_FILES['file']['name'];
        print "<pre>";
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetfile_true)) {
            $data = ['product_id' => input('id'), 'image' => $targetfile_data];
            Db::name('productimage')->strict(false)->insert($data);
            //print "File is valid, and was successfully uploaded.  Here's some more debugging info:\n";
            // print_r($_FILES);
        } else {
            print "上传失败，错误信息如下:\n";
            print_r($_FILES);
        }
    }
    public function deleproductimg(){
        $r=Db::table('sw_productimage')->delete(input('id'));
        if($r){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }

    }
    public function storemanage(){

        $con['limite']=3;
        $con['parent_id']=cookie('user_id');
        $list=User::with('Userinfo','LEFT')->where($con)->select();
        $this->assign('list',$list);
        //$list= User::with(['Userinfo' => function($query) { $query->field('user_id,company,email');}])->select();
        // dump($list);
        return $this->fetch('storemanage');
    }
    public function addstore(){
        if (Request::isPost()) {
            $data = Request::param();
            $id = $data['pro_id'];
            $region = Db::name('region')->where(['parent_id' => $id])->select();

            $opt = '<option>--请选择--</option>';
            foreach($region as $key=>$val){
                $opt .= "<option value='{$val['id']}'>{$val['name']}</option>";
            }
            echo json_encode($opt);
            die;
        }

        $region = Db::name('region')->where(['level_type' => 1])->select();
        $this->assign('region', $region);

        return $this->fetch('addstore');
    }
    public function doaddstore()
    {
        if(Request::isPost())
        {
            $validate = new \app\validate\back\Vuser;
            if (!$validate->check(Request::param()))
            {
                dump($validate->getError());
            }else
            {
                $userdata=Request::param();
                $userdata['parent_id']=cookie('user_id');
                $userdata['userpwd']=md5(input('userpwd'));
                if(cookie('user_limite')==2){
                    $userdata['limite']=3;
                }
                //dump($userdata);
                //die('stop');
                $file = request()->file('info_photo');
                // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move( './uploads');
                if($info){
                    $userdata['image']=$info->getSaveName();
                }else
                { $this->error('图片上传失败！');}

                $mod = new User();
                //品牌店仅可以添加门店

                $modinfo=new Userinfo();

                $modinfo->email=$userdata['email'];
                $modinfo->adword='';
                $modinfo->address=$userdata['address'];
                $modinfo->company=$userdata['company'];
                $modinfo->image=$userdata['image'];
                $modinfo->contact=$userdata['contact'];
                $modinfo->tel=$userdata['tel'];
                $modinfo->latitude=$userdata['latitude'];
                $modinfo->longitude=$userdata['longitude'];

                $mod->userinfo=$modinfo;
                $a=$mod->allowField(['username','userpwd','limite','area','parent_id'])->together('userinfo')->save($userdata);

                if(false === $a){
                    // 验证失败 输出错误信息
                    dump($mod->getError());
                    die;
                }else
                {
                    //die('stop here');
                    //echo $mod->id;
                    //为本地初始化主店已添加的商品
                    $cureuserid=$mod->id;
                    $par_id=Db::table('sw_user')->getFieldById($cureuserid,'parent_id');
                    //同步产品种类
                    $sql=" insert into sw_storeproduct (product_id,del_flag,store_id,newprice) select id as product_id,del_flag,$cureuserid as store_id,price as newprice from sw_product where user_id= $par_id  and id not in (select product_id as id from sw_storeproduct where store_id=$cureuserid)";
                    //echo "</br>添加同步产品种类sql</br>".$sql;
                    Db::execute($sql);//同步完毕
                    //sleep("6");
                    $this->success('新增成功', 'back.home/storemanage');
                }
            }

        }else
        {
            $this->error('非法操作');
        }



    }
    public function modistore(){
        if (Request::has('id'))
        {
            $modid = input('id');
            $user = User::get($modid );
            //echo $user->userinfo->email;
            //dump($user);
            $userareaname=Region::where('id',$user->area)->value('name');
            $p['id']=Region::where('id',$user->area)->value('parent_id');
            $p['name']=Region::where('id',$p['id'])->value('name');
            $pp['id']=Region::where('id',$p['id'])->value('parent_id');
            // $pp['name']=Region::where('id',$p['id'])->value('name');

            $region = Db::name('region')->where(['level_type' => 1])->select();
            $this->assign('userareaname',$userareaname);
            $this->assign('region', $region);
            $this->assign('user',$user);
            $this->assign('p',$p);
            $this->assign('pp',$pp);
            return $this->fetch('modistore');
            //echo $pid."$".$ppid;
        }
        else{

            $this->error('非法操作');

        }

    }
    public function domodistore(){
        $flag=false;
        $modidata=Request::param();
        $modidata['parent_id']=cookie('user_id');

        //如果没有设置新密码则保留旧密码
        if ($modidata['userpwd']==''){
            $flag=true;//设置密码保留标志
            $modidata['userpwd']=Db::table('sw_user')->getFieldById(input('userid'), 'userpwd');
        }
        // die('stop here');
        $isfile=$_FILES;
        if($isfile['info_photo']['name']==''){
            $vimage = Db::table('sw_userinfo')->getFieldByUser_id(input('userid'), 'image');
        }
        else{
            $file = request()->file('info_photo');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->move( './uploads');
            if($info){
                $vimage=$info->getSaveName();
            }else
            { $this->error('图片上传失败！');}
        }
        if(Request::isPost()){
            $validate = new \app\validate\back\Vuser;
            if (!$validate->check($modidata)) {
                dump($validate->getError());
            }else{
                $user = User::get($modidata['userid']);
                $user->username=$modidata['username'];
                if(cookie('limite')==2){
                    $modidata['limite']=3;
                }
                $user->limite= $modidata['limite'];//品牌店仅可以添加门店
                if($flag==false){
                    $user->userpwd=md5($modidata['userpwd']);
                }
                $user->userinfo->email=input('email');
                $modinfo->adword='';
                $user->userinfo->company=input('company');
                $user->userinfo->address=input('address');
                $user->userinfo->image=$vimage;
// 更新当前模型及关联模型
                $res=$user->together('userinfo')->save();
                if($res){

                    $this->success('修改成功', 'back.home/storemanage');
                }else{
                    $this->error('修改失败');
                }
            }

        }else
        {
            $this->error('非法操作');
        }


    }
    public function delestore()
    {
        if (Request::has('id')) {
            $delid = input('id');
            //User::destroy($delid);
            //Userinfo::destroy($delid);

            $data = User::get($delid,'userinfo');
            // 删除当前及关联模型
            $data->together('userinfo')->delete();
            //die('stop here');
            $this->success('删除成功', 'back.home/storemanage');
        } else {
            $this->error('非法操作');
        }
    }
    public function storeproductmanage(){
        $cureuserid=cookie('user_id');
        $par_id=Db::table('sw_user')->getFieldById($cureuserid,'parent_id');
        //echo $par_id;
        // $con['user_id']=$par_id;
        //同步产品种类
        //$sql="select id as product_id,del_flag,$cureuserid as store_id,price as newprice from sw_product where user_id= $par_id  and id not in (select product_id as id from sw_storeproduct where store_id=$cureuserid)";
        //echo "</br>查询同步产品种类sql</br>".$sql;

        //$pdata=Db::query($sql);
        //dump($pdata);

        $sql=" insert into sw_storeproduct (product_id,del_flag,store_id,newprice) select id as product_id,del_flag,$cureuserid as store_id,price as newprice from sw_product where user_id= $par_id  and id not in (select product_id as id from sw_storeproduct where store_id=$cureuserid)";
        //echo "</br>添加同步产品种类sql</br>".$sql;
        Db::execute($sql);//同步完毕

        //同步产品删除标志
        $sql="update sw_storeproduct set del_flag=1 where store_id=$cureuserid and  product_id in (select id as product_id from sw_product where user_id=$par_id and del_flag=1)";
        //echo "</br>同步已删除产品种类sql</br>".$sql;
        Db::execute($sql);


        $sql="select a.id as aid,a.store_id,a.product_id,a.newprice,a.del_flag,a.on_off,b.* from sw_storeproduct a  left join sw_product b  on a.product_id=b.id  where a.store_id=$cureuserid order by a.del_flag ";
        //echo "</br>查询本店产品</br>".$sql;
        $list=Db::query($sql);
        //dump($list);
        $this->assign('list',$list);
        return $this->fetch('storeproductmanage');


    }
    public function setontooff(){
        $id=input('id');
        if(Request::isPost()){
            $res=Db::table('sw_storeproduct')
                ->where('id', $id)
                ->data(['on_off' => 1])
                ->update();
            //$s=Db::name('storeproduct')->getLastSql();
            if ($res){
                echo '1';
            }else{
                echo "3";
                //echo Db::name('storeproduct')->getLastSql();
            }

        }
    }
    public function setofftoon(){
        $id=input('id');
        if(Request::isPost()){
            $res=Db::table('sw_storeproduct')
                ->where('id', $id)
                ->data(['on_off' => 0])
                ->update();
            //$s=Db::name('storeproduct')->getLastSql();
            if ($res){
                echo '0';
            }else{
                echo "2";
                //echo Db::name('storeproduct')->getLastSql();
            }
        }
    }
    public function modistoreprice(){
        return $this->fetch("modistoreprice");

    }
    public function domodistoreprice(){
        if(!is_numeric(input('newprice'))){
            $this->error("价格为非数字，修改失败");
        }
        $newprice=(float)input('newprice');

        $id=input('id');
        if(Request::isPost()){
            $res=Db::table('sw_storeproduct')
                ->where('id', $id)
                ->data(['newprice' =>$newprice])
                ->update();
            //$s=Db::name('storeproduct')->getLastSql();
            if ($res){
                $this->success('修改成功','back.home/storeproductmanage');
            }else{
                echo Db::table('sw_storeproduct')->getLastSql();
                $this->error("修改失败",'back.home/storeproductmanage');
            }
        }

    }
    //旧订单处理
    public function dealorder(){
        $storeid=cookie('user_id');
        $sql="select * from sw_order where storeid='$storeid' order by create_time desc";
        $list=Db::query($sql);
        //dump($list);
        $this->assign('list',$list);
        return $this->fetch('dealorder');

    }
    public function dodealorder(){
        if(Request::isPost()){
            $id=input('id');
            $expressid=input('expressid');
            $basedata = Order::get($id);
            if($basedata['status']==1){
                $this->error('该订单已处理，无法再处理', 'back.home/dealorder');
            }
            if($basedata['takeself']==1 and $expressid<>''){
                $this->error('改订单为自提，无需配送', 'back.home/dealorder');
            }
            if($basedata['takeself']==0 and $expressid==''){
                $this->error('改订单需配送,必须输入配送快递单号', 'back.home/dealorder');
            }
            $data['result']=input('result');
            $data['status']=1;
            $data['expressid']=input('expressid');

            $order = Order::get($id);;
            $res=$order->force()->save($data);
            if($res){
                $codenumber=Db::table('sw_order')->getFieldById($id, 'codenumber');
                $ye=Db::table('sw_codedetail')->getFieldByNumber($codenumber, 'balance');
                $ye=(float)$ye-(float)$basedata['totalnum'];
                Db::name('codedetail')
                    ->where('number', $codenumber)
                    ->data(['balance' => $ye])
                    ->update();
                $this->success('订单处理完毕', 'back.home/dealorder');

            }else{
                $this->error('处理失败', 'back.home/dealorder');
            }

        }

    }
    public function poll(){
        $userid=cookie('user_id');
        if(cookie('user_limite')==1){
            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='2'";
            $vender=Db::query($sql);
            $this->assign('vender',$vender);
        }
        if(cookie('user_limite')==2){
            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='2' and a.id=$userid";
            $vender=Db::query($sql);
            $this->assign('vender',$vender);

            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='3' and a.parent_id=$userid";
            $store=Db::query($sql);
            $this->assign('store',$store);
        }
        if(cookie('user_limite')==3){
            $pid=Db::table('sw_user')->getFieldById($userid,'parent_id');
            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='2' and a.id=$pid";
            $vender=Db::query($sql);
            $this->assign('vender',$vender);

            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='3' and a.id=$userid";
            $store=Db::query($sql);
            $this->assign('store',$store);
        }

        if(Request::isPost()){
            $sqlinfo="订单查询条件：";
            $venderid=Request::param('venderid');
            $storeid=Request::param('storeid');
            $startrq=Request::param('startrq');
            $endrq=Request::param('endrq');
            $sql="select a.*,b.company from sw_order a left join sw_userinfo b on a.storeid=b.user_id where date_format(create_time,'%Y-%m-%d') between '$startrq' and '$endrq'";
            $sql2="select sum(totalnum) as totaldata from sw_order  where date_format(create_time,'%Y-%m-%d') between '$startrq' and '$endrq'";
            if ($venderid<>'0'){
                if($storeid<>'0'){
                    $sql=$sql." and a.storeid='$storeid'  order by a.storeid,a.create_time desc";
                    $sql2=$sql2." and storeid='$storeid' ";
                    $vendername=Db::table("sw_userinfo")->getFieldByUser_id($venderid,'company');
                    $storename=Db::table("sw_userinfo")->getFieldByUser_id($storeid,'company');
                    $sqlinfo="下列查询结果条件为："."商家:".$vendername.",分店:".$storename.",日期在".$startrq."---".$endrq."订单数据";

                }else{

                    $sql=$sql."and a.storeid in (select id as storeid from sw_user where parent_id='$venderid') order by a.storeid,a.create_time desc";
                    $sql2=$sql2."and storeid in (select id as storeid from sw_user where parent_id='$venderid')";
                    $vendername=Db::table("sw_userinfo")->getFieldByUser_id($venderid,'company');
                    $sqlinfo="下列查询结果条件为："."商家".$vendername."所有分店日期在".$startrq."---".$endrq."订单数据";
                }



            }else{
                $sql=$sql." order by a.storeid,a.create_time desc";
                $sqlinfo="下列查询结果条件为："."全部商家包括商家分店日期在".$startrq."---".$endrq."订单数据";

            }

            $this->assign('sql',$sql);
            cookie('user_sql', $sql, 3600); // 一个小时有效期
            $list=Db::query($sql);
            $total=Db::query($sql2);
            $this->assign("list",$list);
            // echo $total[0]['totaldata'];
            $this->assign("total",$total[0]['totaldata']);
            $this->assign('sqlinfo',$sqlinfo);
        }else{

            $this->assign('sql','sql empty');
        }

        return $this->fetch("poll");


    }
    public function choosevender(){
        if (Request::isPost()) {
            $data = Request::param();
            $id = $data['vender_id'];
            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite=3 and a.parent_id=$id";
            $storedate=Db::query($sql);

            $opt = '<option value="0">--全部--</option>';
            foreach($storedate as $key=>$val){
                $opt .= "<option value='{$val['id']}'>{$val['company']}</option>";
            }
            echo json_encode($opt);
            die;
        }

    }
    public function orderout(){
        $cx=cookie('user_sql');
        $hj=Request::param('t');
        //die('stop');
        require_once Env::get('ROOT_PATH').'public/static/PHPExcel/Classes/PHPExcel.php';
        require_once Env::get('ROOT_PATH').'public/static/PHPExcel/Classes/PHPExcel/IOFactory.php';
        //导出
        $path = dirname(__FILE__); //找到当前脚本所在路径
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);


        // 实例化完了之后就先把数据库里面的数据查出来
        $sql =Db::table('sw_codedetail')->query($cx);

        // 设置表头信息
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '订单号')
            ->setCellValue('C1', '下单日期  ')
            ->setCellValue('D1', '订单明细')
            ->setCellValue('E1', '兑换金额')
            ->setCellValue('F1', '提货方式：1自提0配送')
            ->setCellValue('G1', '订单状态：0待处理1已处理2争议订单')
            ->setCellValue('H1', '处理结果:')
            ->setCellValue('I1', '兑换门店');
        /*--------------开始从数据库提取信息插入Excel表中------------------*/

        $i=2;  //定义一个i变量，目的是在循环输出数据是控制行数
        $count = count($sql);  //计算有多少条数据
        for ($i = 2; $i <= $count+1; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $i-1);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $sql[$i-2]['id']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $sql[$i-2]['create_time']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $sql[$i-2]['productinfo']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $sql[$i-2]['totalnum']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $sql[$i-2]['takeself']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $sql[$i-2]['status']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $sql[$i-2]['result']);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $sql[$i-2]['company']);
        }
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "合计：".$hj);

        /*--------------下面是设置其他信息------------------*/

        $objPHPExcel->getActiveSheet()->setTitle('订单明细');      //设置sheet的名称
        $objPHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');   //通过PHPExcel_IOFactory的写函数将上面数据写出来

        $PHPWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel,"Excel2007");

        header('Content-Disposition: attachment;filename="订单明细.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件*/

    }
    public  function patchorder(){
        //die("stop");
        $userid=cookie('user_id');
        if(cookie('user_limite')==1){
            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='2'";
            $vender=Db::query($sql);
            $this->assign('vender',$vender);
        }
        if(cookie('user_limite')==2){
            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='2' and a.id=$userid";
            $vender=Db::query($sql);
            $this->assign('vender',$vender);

            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='3' and a.parent_id=$userid";
            $store=Db::query($sql);
            $this->assign('store',$store);
        }
        if(cookie('user_limite')==3){
            $pid=Db::table('sw_user')->getFieldById($userid,'parent_id');
            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='2' and a.id=$pid";
            $vender=Db::query($sql);
            $this->assign('vender',$vender);

            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='3' and a.id=$userid";
            $store=Db::query($sql);
            $this->assign('store',$store);
        }
        if(Request::isPost() or Request::isGet() ){
            $pageNumber = input('page') ? input('page') : '0';//客户端传过来的分页
            if($pageNumber > 0){
                $pageNumber_one = $pageNumber-1;
            } else {
                $pageNumber_one = 0;
            }
            $limit = 10;//每页显示条数
            $offset = $pageNumber_one * $limit;//查询偏移值
            $sqlinfo="订单查询条件：";

            $order_id=input('order_id') ? input('order_id') : '0';
            $venderid=input('venderid') ? input('venderid') : '0';
            $storeid=input('storeid') ? input('storeid') : '0';
            $type=input('type') ? input('type') : '0';
            $status=input('status') ? input('status') : '0';
            $startrq=input('startrq') ? input('startrq') : date("Y-m-d");;
            $endrq=input('endrq') ? input('endrq') : date("Y-m-d");;


            /*$order_id=Request::param('order_id');
            $venderid=Request::param('venderid');
            $storeid=Request::param('storeid');
            $type=Request::param('type');
            $status=Request::param('status');
            $startrq=Request::param('startrq');
            $endrq=Request::param('endrq');*/

            $sql="select a.*,b.company from sw_neworder a left join sw_userinfo b on a.dealstore_id=b.user_id where date_format(create_time,'%Y-%m-%d') between '$startrq' and '$endrq'";
            $sql2="select sum(total) as totaldata from sw_neworder  where date_format(create_time,'%Y-%m-%d') between '$startrq' and '$endrq'";
            $sql3="select count(*) as count_num from sw_neworder a left join sw_userinfo b on a.dealstore_id=b.user_id where date_format(create_time,'%Y-%m-%d') between '$startrq' and '$endrq'";

            if($type!='0'){
                $sql=$sql." and a.type='$type' ";
                $sql2=$sql2." and type='$type' ";
                $sql3=$sql3." and type='$type' ";
            }

            if($status!='0'){
                $sql=$sql." and a.status='$status' ";
                $sql2=$sql2." and status='$status' ";
                $sql3=$sql3." and status='$status' ";
            }

            if($order_id<>'0'){
                $sql=$sql." and a.order_id='$order_id' ";
                $sql2=$sql2." and order_id='$order_id' ";
                $sql3=$sql3." and order_id='$order_id' ";
            }

            if ($venderid<>'0'){
                if($storeid<>'0'){
                    $sql=$sql." and a.dealstore_id='$storeid'  order by a.dealstore_id,a.create_time desc limit $offset,$limit";
                    $sql2=$sql2." and dealstore_id='$storeid' ";
                    $sql3=$sql3." and dealstore_id='$storeid' ";
                    $vendername=Db::table("sw_userinfo")->getFieldByUser_id($venderid,'company');
                    $storename=Db::table("sw_userinfo")->getFieldByUser_id($storeid,'company');
                    $sqlinfo="下列查询结果条件为："."商家:".$vendername.",分店:".$storename.",日期在".$startrq."---".$endrq."订单类型为".$type."状态为".$status."订单号为".$order_id."订单数据（订单类型:0全部1自提2配送；状态:0全面1未发货2已发货3售后中4完成交易）";
                }else{
                    $sql=$sql."and a.dealstorep_id='$venderid'  order by a.dealstore_id,a.create_time desc limit $offset,$limit";
                    $sql2=$sql2."and dealstorep_id='$venderid'";
                    $sql3=$sql3."and dealstorep_id='$venderid'";
                    $vendername=Db::table("sw_userinfo")->getFieldByUser_id($venderid,'company');
                    $sqlinfo="下列查询结果条件为："."商家".$vendername."所有分店日期在".$startrq."---".$endrq."订单类型为".$type."状态为".$status."订单号为".$order_id."订单数据（订单类型:0全部1自提2配送；状态:0全面1未发货2已发货3售后中4完成交易）";
                }
            }else{
                $sql=$sql." order by a.dealstore_id,a.create_time desc limit $offset,$limit";
                $sqlinfo="下列查询结果条件为："."全部商家包括商家分店日期在".$startrq."---".$endrq."订单类型为".$type."状态为".$status."订单号为".$order_id."订单数据（订单类型:0全部1自提2配送；状态:0全面1未发货2已发货3售后中4完成交易）";

            }

            $this->assign('sql',$sql);
            cookie('user_sql', $sql, 3600); // 一个小时有效期
            $list=Db::query($sql);
            $total=Db::query($sql2);
            $counts = db()->query($sql3);
            $count = $counts['0']['count_num'];
            $pagernator = Bootstrap::make($list,$limit,$pageNumber,$count,false,['path'=>Bootstrap::getCurrentPath(),'query'=>request()->param()]);
            $page = $pagernator->render();
            // dump($page);
            $this->assign('page', $page);

            $this->assign("list",$list);
            // echo $total[0]['totaldata'];
            $this->assign("total",$total[0]['totaldata']);
            $this->assign('sqlinfo',$sqlinfo);
        }else{
            $this->assign('sql','sql empty');
        }
        return $this->fetch("patchorder");
    }
    public function addpatchorder(){
        if(cookie('user_limite')==1){
            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='2'";
            $vender=Db::query($sql);
            //dump($vender);
            $this->assign('vender',$vender);
            $parent_id=$vender[0]['id'];
            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='3' and a.parent_id='$parent_id'" ;
            $store=Db::query($sql);
            $this->assign('store',$store);
            //dump($store);
        }
        return $this->fetch("addpatchorder");
    }
    public function choosevender2(){
        if (Request::isPost()) {
            $data = Request::param();
            $id = $data['vender_id'];
            $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite=3 and a.parent_id=$id";
            $storedate=Db::query($sql);

            $opt = '';
            foreach($storedate as $key=>$val){
                $opt .= "<option value='{$val['id']}'>{$val['company']}</option>";
            }
            echo json_encode($opt);
            die;
        }

    }
    public function doaddpatchorder(){
        if(input("dealstore_id")<=0){
            $this->error('未选择处理机构');
        }
        $isfile=$_FILES;
        $orderdata=Request::param();
        if(Request::isPost())
        {
            $validate = new \app\validate\back\Vneworder;
            if (!$validate->check(Request::param()))
            {
                dump($validate->getError());
            }else
            {
                if($isfile['info_photo']['name']==''){
                    $orderdata['orderimage']='';
                }else{

                    $file = request()->file('info_photo');
                    // 移动到框架应用根目录/uploads/ 目录下
                    $info = $file->move( './uploads');

                    if($info){
                        $orderdata['orderimage']=$info->getSaveName();
                    }else
                    { $this->error('订单图上传失败！');}
                }

                $orderdata['status']=1;
                $mod = new Neworder();
                //dump($userdata);
                //die('stop!');
                $a=$mod->save($orderdata);
                $maxid=$mod->id;
                if($a){
                    $this->success('补录订单成功', 'back.home/patchorder');

                }else
                {
                    dump($mod->getError());
                }
            }

        }else
        {
            $this->error('非法操作');
        }


    }
    public function delepatchorder(){
        if (Request::has('id')) {
            $delid = input('id');

            $user = Neworder::get($delid);
            if($user->status!=1){
                $this->error('已发货订单不得删除','back.home/patchorder');

            }else{
                $user->delete();
                if($user){
                    $this->success('删除成功', 'back.home/patchorder');
                }else{
                    $this->error('删除失败','back.home/patchorder');
                }

            }


        } else {
            $this->error('非法操作');
        }

    }
    public function pauseorder(){
        $id=input('id');
        if(Request::isGet()){
            $res=Db::table('sw_neworder')
                ->where('id', $id)
                ->data(['status' => 3])
                ->update();
            //$s=Db::name('storeproduct')->getLastSql();
            if ($res){
                $this->success('已置争议', 'back.home/patchorder');
            }else{
                $this->error('置争议失败','back.home/patchorder');
            }
        }
    }
    public function modipatchorder(){
        if (Request::has('id'))
        {
            $modid = input('id');
            $orderdata = Neworder::get($modid );
            if($orderdata->status!=1){
                $this->error('已发货订单不得修改','back.home/patchorder');

            }else{
                $psid=$orderdata['dealstorep_id'];
                $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.limite='2'";
                $venderdata=Db::query($sql);
                // dump($venderdata);
                $sql="select a.id,b.company from sw_user a left join sw_userinfo b on a.id=b.user_id where a.parent_id='$psid'";
                $storedata=Db::query($sql);
                // dump($storedata);
                $this->assign('venderdata',$venderdata);
                $this->assign('storedata',$storedata);
                $this->assign('orderdata',$orderdata);
                return $this->fetch('modipatchorder');}
            //echo $pid."$".$ppid;
        }
        else{

            $this->error('非法操作');

        }

    }
    public function domodipatchorder(){
        $userdata=Request::param();
        $isfile=$_FILES;
        if($isfile['info_photo']['name']==''){
            $userdata['orderimage']=Db::table('sw_neworder')->getFieldById(input('id'),'orderimage');
        }else{
            $file = request()->file('info_photo');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->move( './uploads');
            if($info){
                $userdata['orderimage']=$info->getSaveName();
            }else
            { $this->error('图片上传失败！');}}

        if(Request::isPost())
        {
            $validate = new \app\validate\back\Vneworder;
            if (!$validate->check(Request::param()))
            {
                dump($validate->getError());
            }else
            {
                $mod = Neworder::get(input('id'));
                $a=$mod->save($userdata);

                if(false === $a){
                    // 验证失败 输出错误信息
                    dump($mod->getError());
                    die;
                }else
                {
                    $this->success('修改成功', 'back.home/patchorder');
                }
            }

        }else
        {
            $this->error('非法操作');
        }

    }
    public function finishpatchorder(){
        $id=input('id');
        $status=Db::table('sw_neworder')->getFieldById($id,'status');
        if($status==1){
            $this->error("未发货订单不能完成交易操作", 'back.home/patchorder');
        }
        if(Request::isGet()){
            $res=Db::table('sw_neworder')
                ->where('id', $id)
                ->data(['status' => 4])
                ->update();
            //$s=Db::name('storeproduct')->getLastSql();
            if ($res){
                $this->success('修改状态成功', 'back.home/patchorder');
            }else{
                $this->success('修改状态失败', 'back.home/patchorder');
            }
        }

    }
    public function dealneworder(){

        if(!Request::isPost()){
            $this->error('无法处理订单');
        }
        $newdata=Request::param();
        //dump($newdata);
        $newdata["deal_time"]=date('Y-m-d h:i:s', time());

        $order=Neworder::get(input('id'));
        if($order['status']=='4'){
            $this->error('该订单已完成交易，无法再次处理','back.home/patchorder');
        }
        if($order['status']=='1'){
            $newdata["status"]='2';
        }
        if($order['type']=='1'){
            $companyname=Db::table('sw_userinfo')->getFieldByUser_id(input('deal_user'),'company');
            $newdata['pickplace']=$companyname;
        }
        // dump($newdata);
        //die('stop');
        $res=$order->save($newdata);

        if($res){
            $codenumber=Db::table('sw_neworder')->getFieldById($order['id'], 'codenumber');
            $ye=Db::table('sw_codedetail')->getFieldByNumber($codenumber, 'balance');
            $ye=(float)$ye-(float)$order['total'];
            Db::name('codedetail')
                ->where('number', $codenumber)
                ->data(['balance' => $ye])
                ->update();

            $this->success('订单处理成功','back.home/patchorder');
        }else{
            $this->error('订单处理失败');
        }

    }
    public function checkneworder()
    {
        if (Request::isPost() or Request::isGet()) {
            $id = input("id");
            $checkdata = Db::table('sw_neworder')->field('id,type,dispatchname,dispatchtel,dispatchname,dispatchaddress,pickname,picktel')->where('id', $id)->find();
            foreach ( $checkdata as $key => $value ) {
                $checkdata[$key] = urlencode ( $value );
            }
            echo urldecode (json_encode ($checkdata));
            // $str = json_encode($checkdata);
            //echo $str;
        }
    }
}
