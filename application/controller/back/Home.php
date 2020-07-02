<?php
namespace app\controller\back;
use think\facade\Env;
use think\Db;
use think\facade\Request;
use think\Controller;

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
    public function demo()
    {
        //return $this->fetch('demo');
        echo "demo";
    }





    }
