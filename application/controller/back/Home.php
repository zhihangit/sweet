<?php
namespace app\controller\back;
use think\facade\Env;
use think\Controller;

class Home extends Controller
{
    public function index()
    {
        //echo  "juse test";
        $this->assign('todaytime', time());
       // echo  "juse  home test";
        return $this->fetch('index');
    }
    public function loginout()
    {
        cookie('user_id', null);
        cookie('user_name', null);
        $this->redirect(url('back.login/index'));
    }

    public function demo()
    {
        return $this->fetch('demo');
    }




    }
