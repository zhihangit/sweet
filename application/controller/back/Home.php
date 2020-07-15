<?php
namespace app\controller\back;
use app\model\back\Userinfo;
use think\facade\Env;
use think\Db;
use think\facade\Request;
use think\Controller;
use app\model\back\User;
use app\model\back\Code;
use app\model\back\Client;
use app\model\back\Region;
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
    //退出系统
    public function loginout()
    {
        cookie('user_id', null);
        cookie('user_name', null);
        cookie('limite', null);
        $this->redirect(url('back.login/index'));
    }
    //商家管理
    public function comadmin()
    {
        return $this->fetch('comadmin');
    }
    public function vendermanage(){
        $list=User::with('Userinfo')->select();
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
            $info = $file->move( '../uploads');
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
            $user->limite= $modidata['limite'];
            if($flag==false){
                $user->userpwd=md5($modidata['userpwd']);
            }
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
                                $file = request()->file('info_photo');
                              // 移动到框架应用根目录/uploads/ 目录下
                                 $info = $file->move( '../uploads');
                                if($info){
                                             $userdata['image']=$info->getSaveName();
                                         }else
                                             { $this->error('图片上传失败！');}

                                $mod = new User();
                                $modinfo=new Userinfo();

                                $modinfo->email=$userdata['email'];
                                $modinfo->address=$userdata['address'];
                                $modinfo->company=$userdata['company'];
                                $modinfo->image=$userdata['image'];

                                $mod->userinfo=$modinfo;
                                $a=$mod->allowField(['username','userpwd','limite','area'])->together('userinfo')->save($userdata);

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
        $clientlist = Db::name('client')->select();
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
                    $info = $file->move( '../uploads');

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
/*            $modidata=Request::param();

            if($isfile['contract']['name']==''){
                $vfile = Db::table('sw_code')->getFieldById(input('id'), 'contract');
            }
            else{
                $file = request()->file('contract');
                // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move( '../uploads');
                if($info){
                    $vfile=$info->getSaveName();
                }else
                { $this->error('合同上传失败！');}
            }*/
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
                        $info = $file->move( '../uploads');

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
             ->setCellValue('B1', '批次号	')
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
        if(Request::isPost())
        {
                $userdata['create_time']=date('Y-m-d H:i:s',time());
                dump($userdata);
                //die('stop!');
                $a=Db::name('client')->Field(['companyname','email','companyaddress','contact','tel'])->insert($userdata);

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




    }
