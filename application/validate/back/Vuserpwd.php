<?php
namespace app\validate\back;
use think\Validate;
class Vuserpwd extends Validate
{
    //写规则
    protected $rule = [
        'userpwd' => 'require|max:32',
    ];
    //写报错返回信息
    protected $message = [
        'userpwd.require' => '密码必须填写',
        'userpwd.max' => '密码不能超过32个字符',
    ];
    protected $scene = [
        'edit' => ['userpwd'],
    ];
}