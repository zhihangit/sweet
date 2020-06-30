<?php
namespace app\controller\back;
use think\facade\Env;
use think\Controller;

class Home extends Controller
{
    protected $beforeActionList = [
        'judge'=>  ['except'=>'loginout'],
    ];

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
    public function loginout()
    {
        cookie('user_id', null);
        cookie('user_name', null);
        cookie('limite', null);
        $this->redirect(url('back.login/index'));
    }

    public function demo()
    {
        //return $this->fetch('demo');
        echo "demo";
    }




    }
