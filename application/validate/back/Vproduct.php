<?php
namespace app\validate\back;
use think\Validate;
class Vproduct extends Validate
{
    //写规则
    protected $rule = [
        'name' => 'require',
        'price' => 'require|float',
    ];
    //写报错返回信息
    protected $message = [
        'name.require' => '商品名称必须填写',
        'price.require' => '密码必须填写',
        'price.float' => '价格必须为数字',
    ];
    protected $scene = [
        'add' => ['name','price'],
        'edit' => ['price'],
    ];
}