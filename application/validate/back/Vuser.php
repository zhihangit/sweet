<?php
namespace app\validate\back;
use think\Validate;
class Vuser extends Validate
{
    //写规则
    protected $rule = [
        'username' => 'require|max:12',
        'userpwd' => 'require|max:32',
        'username'   => 'unique:user',
        //'limite'=>'require',
    ];
    //写报错返回信息
    protected $message = [
        'username.require' => '用户名必须填写',
        'username.max' => '用户名不能超过12个字符',
        'userpwd.require' => '密码必须填写',
        'userpwd.max' => '密码不能超过15个字符',
        'userpwd.unique:user' => '已有该用户，请用本用户名直接登录或注册其他用户名',
        //'limite'=>'未选择商家类型',
    ];
    protected $scene = [
        'add' => ['username','userpwd'],
        'edit' => ['userpwd'],
    ];
}