<?php
namespace app\controller\back;
use think\Controller;

class Login extends Controller
{
    public function index()
    {

        return $this->fetch('index');
    }


    public function dologin()
    {
        $param = input('post.');
        if(empty($param['username'])){
            $this->error('用户名不能为空');
        }
        if(empty($param['userpwd'])){
            $this->error('密码不能为空');
        }
// 验证用户名
        $has = db('user')->where('username', $param['username'])->find();
        if(empty($has)){
            $this->error('用户名密码错误');
        }
// 验证密码
        if($has['userpwd'] != $param['userpwd']){
            $this->error('用户名密码错误');
        }
// 记录用户登录信息
        cookie('user_id', $has['id'], 3600); // 一个小时有效期
        cookie('user_name', $has['username'], 3600);
        $this->redirect(url('back.home/index'));
    }
}
