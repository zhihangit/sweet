<?php
namespace app\controller\back;
use app\model\back\Userinfo;
use think\facade\Env;
use think\Db;
use think\facade\Request;
use think\Controller;
use app\model\back\User;
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
        //$list= User::with(['Userinfo'	=> function($query) { $query->field('user_id,company,email');}])->select();
       // dump($list);
        return $this->fetch('vendermanage');
    }

    public function codemanage(){

        return $this->fetch('codemanage');
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
    public function doaddvender(){
        if(Request::isPost()){

            $validate = new \app\validate\back\Vuser;
            if (!$validate->check(Request::param())) {
                dump($validate->getError());
            }else{

                $userdata=Request::param();
                $file = request()->file('info_photo');
                // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move( '../uploads');
                if($info){
                    $userdata['image']=$info->getSaveName();
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
    public function demo()
    {
        //$result=User::select();
        //return json($result);

        /*$user           = new User;
        $user->name     = 'thinkphp';
        //$user->email    = 'thinkphp@qq.com';
        $user->save();
        echo "demo";*/
        //自动时间
        /*$user = new User();
        $user->username = 'thinkphp';
        $user->userpwd = md5('987001');
        $user->limite =1;
        $user->areaid=1;
        $user->aid=2;
        $user->bid=3;
        $user->save();
        echo $user->create_time; // 输出类似 2016-10-12 14:20:10
        echo $user->update_time;*/

        $user = User::get(1);
// 输出Profile关联模型的email属性
        echo $user->profile->email."1</br>";

        die('stop here');
        //sleep(5);
        echo '5sss';


    }





    }
