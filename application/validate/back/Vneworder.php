<?php
namespace app\validate\back;
use think\Validate;
class Vneworder extends Validate
{

    //写规则
    protected $rule = [
        'order_id' => 'require'
    ];
    //写报错返回信息
    protected $message = [
        'order_id.require' => '订单号必须填写',
    ];
}